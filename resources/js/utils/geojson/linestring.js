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
export function calculateLength(geojson) {
    validateLineString(geojson);
    const distances = calculateDistances(geojson);
    return distances.reduce((a, b) => a + b, 0);
}

export function calculateHeightPoints(geojson) {
    validateLineString(geojson);
    const distances = calculateDistances(geojson);
    let heightPoints = [{ distance: 0, height: geojson.coordinates[0][2] }];
    distances.forEach((distance, index) => {
        heightPoints.push({ distance: distance + heightPoints[index].distance, height: geojson.coordinates[index + 1][2] });
    });
    console.log(heightPoints);
    return heightPoints;
}

export function calculateAscent(geojson) {
    const heightPoints = calculateHeightPoints(geojson);
    let ascent = 0;
    for (let i = 0; i < heightPoints.length - 1; i++) {
        if (heightPoints[i].height < heightPoints[i + 1].height) {
            ascent += heightPoints[i + 1].height - heightPoints[i].height;
        }
    }
    return ascent;
}

export function calculateDescent(geojson) {
    const heightPoints = calculateHeightPoints(geojson);
    let descent = 0;
    for (let i = 0; i < heightPoints.length - 1; i++) {
        if (heightPoints[i].height > heightPoints[i + 1].height) {
            descent += heightPoints[i].height - heightPoints[i + 1].height;
        }
    }
    return descent;
}

export function calculateHikingTime(geojson, speed = 4.2) {
    const length = calculateLength(geojson);
    const ascent = calculateAscent(geojson);
    const descent = calculateDescent(geojson);

    // https://www.wanderwege-infrastruktur.ch/de/downloads/hilfsmittel-signalisation
    // const c0 = 14.271;
    // const c1 = 0.36992;
    // const c2 = 0.025922;
    // const c3 = -0.0014384;
    // const c4 = 0.000032105;
    // const c5 = 0.0000081542;
    // const c6 = -0.000000090261;
    // const c7 = -0.000000020757;
    // const c8 = 0.00000000010192;
    // const c9 = 0.000000000028588;
    // const c10 = -0.000000000000057466;
    // const c11 = -0.000000000000021842;
    // const c12 = 1.5176E-17;
    // const c13 = 8.6894E-18;
    // const c14 = -1.3584E-21;
    // const c15 = -1.4026E-21;

    let lkm = (length / 1000) + (ascent / 100) + (descent / 200);
    let t = lkm / speed;
    return t * 60;
}

export function hasElevation(geojson) {
    validateLineString(geojson);
    return geojson.coordinates.every(coordinate => {
        if (coordinate.length !== 3) {
            return false;
        }
        return true;
    });
}

export async function getElevation(geojson) {
    if (hasElevation(geojson)) {
        return geojson;
    }
    const coordinates = geojson.coordinates.map(coordinate => ({ latitude: coordinate[1], longitude: coordinate[0] }));
    // const response = await fetch('https://api.open-elevation.com/api/v1/lookup', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'Accept': 'application/json'
    //     },
    //     body: JSON.stringify({ locations: coordinates })
    // });
    let url = 'https://api.open-elevation.com/api/v1/lookup?locations=';
    coordinates.forEach((coordinate, index) => {
        url += `${coordinate.latitude},${coordinate.longitude}`;
        if (index < coordinates.length - 1) {
            url += '|';
        }
    });
    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        // body: JSON.stringify({ locations: coordinates })
    });
    const data = await response.json();
    console.log(data);

    geojson.coordinates = geojson.coordinates.map((coordinate, index) => {
        coordinate[2] = data.results[index].elevation;
        return coordinate;
    });

    return geojson;
}