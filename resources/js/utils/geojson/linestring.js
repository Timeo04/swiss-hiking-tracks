/**
 * LineString-Objekt validieren
 * @param {*} geojson 
 * @returns {Boolean}
 */
function validateLineString(geojson) {
    // Falls das GeoJSON-Objekt kein LineString ist, weniger als 2 Koordinaten hat oder nicht alle Koordinaten gültig sind, wird ein Fehler erzeugt
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

/**
 * Distanzen zwischen Koordinaten in einem GeoJSON-Objekt vom Typ LineString berechnen
 * @param {*} geojson 
 * @returns {Array} - Distanzen zwischen Koordinaten in Metern
 */
function calculateDistances(geojson) {
    let distances = [];
    // Für jede Koordinate die Distanz zum nächsten Punkt berechnen
    for (let i = 0; i < geojson.coordinates.length - 1; i++) {
        const [lon1, lat1] = geojson.coordinates[i];
        const [lon2, lat2] = geojson.coordinates[i + 1];
        distances.push(calculateDistanceBetweenCoordinates([lat1, lon1], [lat2, lon2]));
    }
    // Distanzen-Array zurückgeben
    return distances;
}

/**
 * Minimale Entfernung zwischen zwei Koordinaten in einem GeoJSON-Objekt vom Typ LineString berechnen
 * @param {*} geojson - GeoJSON-LineString-Objekt
 * @returns {Number} - Die kürzeste Entfernung zwischen zwei Koordinaten in Metern
 */
export function getMinDistance(geojson) {
    // LineString validieren
    validateLineString(geojson);
    // Distanzen zwischen Koordinaten berechnen
    const distances = calculateDistances(geojson);
    // Kleinste Distanz zurückgeben
    return Math.min(...distances);
}

/**
 * Länge eines GeoJSON-Objekts vom Typ LineString berechnen
 * @param {*} geojson - GeoJSON-LineString-Objekt
 * @returns {Number} - Die Gesamtlänge des LineStrings in Metern
 */
export function calculateLength(geojson) {
    // LineString validieren
    validateLineString(geojson);
    // Distanzen zwischen Koordinaten berechnen
    const distances = calculateDistances(geojson);
    // Alle Distanzen addieren und zurückgeben
    return distances.reduce((a, b) => a + b, 0);
}


export function calculateHeightPoints(geojson) {
    // Linestring validieren
    validateLineString(geojson);
    // Distanzen zwischen Koordinaten berechnen
    const distances = calculateDistances(geojson);
    // Ersten Höhenpunkt hinzufügen
    let heightPoints = [{ distance: 0, height: geojson.coordinates[0][2] }];
    distances.forEach((distance, index) => {
        // Höhenpunkt hinzufügen
        heightPoints.push({ distance: distance + heightPoints[index].distance, height: geojson.coordinates[index + 1][2] });
    });
    // Höhenpunkte zurückgeben
    return heightPoints;
}

/**
 * Anstieg in Metern berechnen
 * @param {*} geojson 
 * @returns {Number} - Anstieg in Metern
 */
export function calculateAscent(geojson) {
    // Höhenpunkte berechnen
    const heightPoints = calculateHeightPoints(geojson);
    // Anstieg berechnen
    let ascent = 0;
    for (let i = 0; i < heightPoints.length - 1; i++) {
        // Anstieg zwischen zwei Punkten berechnen und addieren
        if (heightPoints[i].height < heightPoints[i + 1].height) {
            ascent += heightPoints[i + 1].height - heightPoints[i].height;
        }
    }
    // Anstieg in Metern zurückgeben
    return ascent;
}

/**
 * Abstieg in Metern berechnen
 * @param {*} geojson 
 * @returns {Number} - Abstieg in Metern
 */
export function calculateDescent(geojson) {
    // Höhenpunkte berechnen
    const heightPoints = calculateHeightPoints(geojson);
    // Abstieg berechnen
    let descent = 0;
    for (let i = 0; i < heightPoints.length - 1; i++) {
        // Abstieg zwischen zwei Punkten berechnen und addieren
        if (heightPoints[i].height > heightPoints[i + 1].height) {
            descent += heightPoints[i].height - heightPoints[i + 1].height;
        }
    }
    // Abstieg in Metern zurückgeben
    return descent;
}

/**
 * Wanderzeit in Minuten berechnen
 * @param {*} geojson 
 * @param {Number} speed 
 * @returns {Number} - Wanderzeit in Minuten
 */
export function calculateHikingTime(geojson, speed = 4.2) {
    // Länge, Aufstieg und Abstieg berechnen
    const length = calculateLength(geojson);
    const ascent = calculateAscent(geojson);
    const descent = calculateDescent(geojson);

    // Leistungskilometer berechnen
    let lkm = (length / 1000) + (ascent / 100) + (descent / 200);
    // Wanderzeit berechnen
    let t = lkm / speed;
    // Wanderzeit in Minuten zurückgeben
    return t * 60;
} 5