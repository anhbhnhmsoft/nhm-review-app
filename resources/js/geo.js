export const GeoPlugin = {
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
    hasStoredLocation() {
        const lat = localStorage.getItem('userLat');
        const lng = localStorage.getItem('userLng');
        return lat && lng && !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng));
    },
    getStoredLocation() {
        const lat = localStorage.getItem('userLat');
        const lng = localStorage.getItem('userLng');
        if (lat && lng) {
            return {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            };
        }
        return null;
    },
    storeLocation(lat, lng) {
        localStorage.setItem('userLat', lat.toString());
        localStorage.setItem('userLng', lng.toString());
    },
    clearStoredLocation() {
        localStorage.removeItem('userLat');
        localStorage.removeItem('userLng');
    },
    getCurrentLocation() {
        const defaultOptions = {
            enableHighAccuracy: true,
            maximumAge: 0,
        };
        return new Promise((resolve, reject) => {
            if (!('geolocation' in navigator)) {
                return reject(new Error('Trình duyệt không hỗ trợ Geolocation.'));
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const {
                        latitude, longitude, accuracy,
                        altitude, altitudeAccuracy, heading, speed
                    } = pos.coords;
                    resolve({
                        lat: latitude,
                        lng: longitude,
                        accuracy,
                        altitude,
                        altitudeAccuracy,
                        heading,
                        speed,
                        timestamp: pos.timestamp,
                    });
                },
                (err) => {
                    const msg = {
                        1: 'Bạn đã từ chối quyền truy cập vị trí.',
                        2: 'Không xác định được vị trí hiện tại.',
                        3: 'Quá thời gian chờ lấy vị trí.',
                    }[err.code] || err.message;
                    reject(new Error(msg));
                },
                defaultOptions
            );
        });
    }
};
