// toGeoJson-Library importieren, um .gpx und .kml Dateien in GeoJSON umzuwandeln
import toGeoJson from "@mapbox/togeojson";

// Dateiinhalt laden und in GeoJSON umwandeln
export async function parseGeofile(file) {
  // Dateiinhalt laden
  let fileContent = null;
  await loadFileContent(file).then((content) => {
    fileContent = content;
  });

  // Zu GeoJSON konvertieren und zurückgeben
  return convertToGeoJson(fileContent, file.name.trim().split(".").pop());
}

// Dateiinhalt mit FileReader laden
async function loadFileContent(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = (event) => {
      resolve(event.target.result);
    };
    reader.onerror = (event) => {
      reject(event.target.error);
    };
    reader.readAsText(file);
  });
}

// GeoJSON aus XML-Datei umwandeln
function convertToGeoJson(data, fileType = "gpx") {
  // Konvertierung abhängig vom Dateityp
  if (fileType === "gpx") {
    let dom = (new DOMParser()).parseFromString(data, "text/xml");
    return toGeoJson.gpx(dom, { styles: false });
  } else if (fileType === "kml") {
    let dom = (new DOMParser()).parseFromString(data, "text/xml");
    return toGeoJson.kml(dom, { styles: false });
  } else if (fileType === "geojson") {
    // Datei direkt zurückgeben, falls Format bereits korrekt
    let geojson = JSON.parse(data);
    validateGeoJson(geojson);
    return geojson;
  } else {
    // Fehlermeldung bei nicht unterstütztem Dateiformat
    throw new Error("Dateiformat wird nicht unterstützt.");
  }
}

// GeoJSON-Datei nach relevanten Informationen filtern
export function filterGeoJson(geoJson) {
  if (geoJson.type === "FeatureCollection") {
    // Nur LineString-Features mit mindestens zwei Koordinaten zulassen
    geoJson.features = geoJson.features.filter((feature) => validateFeature(feature));
  } else if (geoJson.type === "Feature") {
    // Nur LineString-Features mit mindestens zwei Koordinaten zulassen
    if (!validateFeature(geoJson)) {
      throw new Error("Die Route muss einen Pfad beinhalten.");
    }
  }
  return geoJson;
}

export function validateGeoJson(geoJson) {
  if (geoJson.type === "FeatureCollection") {
    // Nur eine Route zulassen
    if (geoJson.features.filter((feature) => validateFeature(feature)).length != 1) {
      throw new Error("Es sind nur Dateien mit einem Pfad erlaubt.");
    }
  } else if (geoJson.type === "Feature") {
    // Nur LineString-Features mit mindestens zwei Koordinaten zulassen 
    if (!validateFeature(geoJson)) {
      throw new Error("Die Route kann nicht geladen werden.");
    }
  }
  return true;
}

// Überprüfen, ob Feature ein gültiger Pfad mit mind. 2 Koordinaten ist
function validateFeature(feature) {
  if (feature.geometry.type != "LineString") {
    return false;
  } else {
    if (feature.geometry.coordinates.length < 2) {
      return false;
    }
  }
  return true;
}

// LineString aus GeoJSON-Datei extrahieren
export function getLineString(geoJson) {
  // Überprüfen, ob nur eine Route vorhanden ist
  validateGeoJson(geoJson);
  
  if (geoJson.type === "LineString") {
    return geoJson;
  } else if (geoJson.type === "FeatureCollection") {
    return getLineString(geoJson.features[0]);
  } else if (geoJson.type === "Feature") {
    if (geoJson.geometry.type === "LineString") {
      return geoJson.geometry;
    } else {
      throw new Error("Die Route muss einen Pfad beinhalten.");
    }
  }
}