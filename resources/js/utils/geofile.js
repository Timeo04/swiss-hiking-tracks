import toGeoJson from "@mapbox/togeojson";

export async function parseGeofile(file) {
  console.log("parseGeofile", file);
  let fileContent = null;
  await loadFileContent(file).then((content) => {
    fileContent = content;
  });
  console.log("content", fileContent);
  let geoJson = null;
  if (file.name.endsWith(".gpx")) {
    geoJson = convertToGeoJson(fileContent, "gpx");
  } else if (file.name.endsWith(".kml")) {
    geoJson = convertToGeoJson(fileContent, "kml");
  } else if (file.name.endsWith(".geojson")) {
    geoJson = JSON.parse(fileContent);
  } else {
    console.error("Filetype not supported");
    return false;
  }

  console.log("geoJson", geoJson);
  return geoJson;
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

function convertToGeoJson(xmlData, fileType = "gpx") {
  let dom = (new DOMParser()).parseFromString(xmlData, "text/xml");
  if (fileType === "gpx") {
    return toGeoJson.gpx(dom, { styles: false });
  } else if (fileType === "kml") {
    return toGeoJson.kml(dom, { styles: false });
  }
}

export function filterGeoJson(geoJson) {
  if (geoJson.type === "FeatureCollection") {
    geoJson.features = geoJson.features.filter((feature) => validateFeature(feature));
  } else if (geoJson.type === "Feature") {
    if (!validateFeature(geoJson)) {
      throw new Error("Die Route muss einen Pfad beinhalten.");
    }
  }
  geoJson.features.forEach((feature) => {
    if (feature.properties && feature.properties.time) {
      delete feature.properties.time;
    }
    if (feature.properties && feature.properties.coordTimes) {
      delete feature.properties.coordTimes;
    }
  });
  console.log("filtered geoJson", geoJson);
  return geoJson;
}

export function validateGeoJson(geoJson) {
  if (geoJson.type === "FeatureCollection") {
    if (geoJson.features.length != 1) {
      throw new Error("Es sind nur Dateien mit einer Route erlaubt.");
    }
    geoJson.features.forEach((feature) => {
      if (!validateFeature(feature)) {
        throw new Error("Die Route kann nicht geladen werden.");
      }
    });
  } else if (geoJson.type === "Feature") {
    if (!validateFeature(geoJson)) {
      throw new Error("Die Route kann nicht geladen werden.");
    }
  }
  return true;
}

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

export function getLineString(geoJson) {
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