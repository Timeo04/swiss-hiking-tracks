function validateLineString(geojson) {
    if (geojson.type !== 'LineString' ||
        geojson.coordinates.length < 2 ||
        !geojson.coordinates.every(coordinate => Array.isArray(coordinate) && coordinate.length >= 2)) {
        throw new Error('Dies ist kein gültiges GeoJSON-Objekt vom Typ LineString');
    }
    return true;
}

/**
* Berechnet die Entfernung zwischen zwei Koordinaten in Metern
* unter Verwendung des Haversine-Formel.
* Quelle: https://www.movable-type.co.uk/scripts/latlong.html
* @param {[number, number]} lat1_lon1 - Koordinaten des ersten Punktes, im Format [latitude, longitude]
* @param {[number, number]} lat2_lon2 - Koordinaten des zweiten Punktes, im Format [latitude, longitude]
* @returns {Number} - Entfernung in Metern
 */
function calculateDistanceBetweenCoordinates([lat1, lon1], [lat2, lon2]) {
    const R = 6371e3; // metres
    const φ1 = lat1 * Math.PI / 180; // φ, λ in radians
    const φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180;
    const Δλ = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) *
        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c; // in metres
}

function calculateDistances(geojson) {
    let distances = [];
    for (let i = 0; i < geojson.coordinates.length - 1; i++) {
        const [lon1, lat1] = geojson.coordinates[i];
        const [lon2, lat2] = geojson.coordinates[i + 1];
        distances.push(calculateDistanceBetweenCoordinates([lat1, lon1], [lat2, lon2]));
    }
    console.log(distances);
    return distances;
}

/**
 * Minimale Entfernung zwischen zwei Koordinaten in einem GeoJSON-Objekt vom Typ LineString berechnen
 * @param {*} geojson - GeoJSON-LineString-Objekt
 * @returns {Number} - Die kürzeste Entfernung zwischen zwei Koordinaten in Metern
 */
export function getMinDistance(geojson) {
    validateLineString(geojson);
    const distances = calculateDistances(geojson);
    console.log("Min distance: ", Math.min(...distances), "meters");
    console.log("Max distance: ", Math.max(...distances), "meters");
    console.log("Average distance: ", distances.reduce((a, b) => a + b, 0) / distances.length, "meters");
    return Math.min(...distances);
}

/**
 * Länge eines GeoJSON-Objekts vom Typ LineString berechnen
 * @param {*} geojson - GeoJSON-LineString-Objekt
 * @returns {Number} - Die Gesamtlänge des LineStrings in Metern
 */
export function getLength(geojson) {
    validateLineString(geojson);
    const distances = calculateDistances(geojson);
    return distances.reduce((a, b) => a + b, 0);
}