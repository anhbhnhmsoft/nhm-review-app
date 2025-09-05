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
    init() {
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                GeoPlugin.state.user.lat = pos.coords.latitude;
                GeoPlugin.state.user.lng = pos.coords.longitude;
                
                window.dispatchEvent(new CustomEvent('geolocation-updated', {
                    detail: { lat: pos.coords.latitude, lng: pos.coords.longitude }
                }));
            }, function () {
                window.dispatchEvent(new CustomEvent('geolocation-error'));
            }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 });
        }
    }
};

window.GeoPlugin = GeoPlugin;
document.addEventListener('DOMContentLoaded', () => GeoPlugin.init());
export default GeoPlugin;