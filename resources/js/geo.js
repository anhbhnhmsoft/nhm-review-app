const GeoPlugin = {
    state: {
        user: { lat: null, lng: null },
    },
    util: {
        toRad(value) { return value * Math.PI / 180; },
        haversineDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = GeoPlugin.util.toRad(lat2 - lat1);
            const dLon = GeoPlugin.util.toRad(lon2 - lon1);
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(GeoPlugin.util.toRad(lat1)) * Math.cos(GeoPlugin.util.toRad(lat2)) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        },
    },
    dom: {
        storeNodes() { return document.querySelectorAll('[data-store-lat][data-store-lng]'); },
        distanceBadges() { return document.querySelectorAll('.distance-badge'); },
    },
    actions: {
        updateDistances(userLat, userLng) {
            GeoPlugin.dom.storeNodes().forEach(function (el) {
                const lat = parseFloat(el.getAttribute('data-store-lat'));
                const lng = parseFloat(el.getAttribute('data-store-lng'));
                if (!isNaN(lat) && !isNaN(lng)) {
                    const km = GeoPlugin.util.haversineDistance(userLat, userLng, lat, lng);
                    const badge = el.querySelector('.distance-badge');
                    if (badge) badge.textContent = `${km.toFixed(1)} km từ vị trí của bạn`;
                }
            });
        },
        setAllBadges(text) {
            GeoPlugin.dom.distanceBadges().forEach(function (b) { b.textContent = text; });
        },
    },
    init() {
        if (!GeoPlugin.dom.storeNodes().length) return;
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                GeoPlugin.state.user.lat = pos.coords.latitude;
                GeoPlugin.state.user.lng = pos.coords.longitude;
                GeoPlugin.actions.updateDistances(GeoPlugin.state.user.lat, GeoPlugin.state.user.lng);
            }, function () {
                GeoPlugin.actions.setAllBadges('Không lấy được vị trí của bạn');
            }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 });
        } else {
            GeoPlugin.actions.setAllBadges('Thiết bị không hỗ trợ định vị');
        }
    }
};

window.GeoPlugin = GeoPlugin;
document.addEventListener('DOMContentLoaded', () => GeoPlugin.init());
document.addEventListener('livewire:navigated', () => GeoPlugin.init());
export default GeoPlugin;