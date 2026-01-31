<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pasong Tamo Disaster Map</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

  <style>
    #map { height: 100%; min-height: 480px; }
    .leaflet-popup-content-wrapper { border-radius: .5rem; }
  </style>
</head>
<body class="bg-gray-100 h-screen flex">

  <!-- Sidebar -->
  <aside class="w-80 bg-white shadow-lg flex flex-col p-5 gap-6">
    <div class="flex items-center gap-3">
      <div class="h-12 w-12 bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-white font-bold rounded-lg">DT</div>
      <div>
        <h1 class="text-xl font-bold">Pasong Tamo Disaster Map</h1>
        <p class="text-sm text-gray-500">Community Preparedness</p>
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-600">Search Location</label>
      <div class="flex gap-2 mt-1">
        <input id="searchInput" type="text" placeholder="Lat,Lng" class="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <button id="btnSearch" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Go</button>
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-600">Base Layers</label>
      <div class="flex flex-col gap-2 mt-1">
        <button id="btnSatellite" class="text-left px-3 py-2 border rounded hover:bg-gray-100">Satellite (Esri)</button>
        <button id="btnOSM" class="text-left px-3 py-2 border rounded hover:bg-gray-100">OpenStreetMap</button>
        <button id="btnZoomEarth" class="text-left px-3 py-2 border rounded hover:bg-gray-100">Zoom Earth Frame</button>
      </div>
    </div>

    <div>
      <button id="btnAddMarker" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add Marker</button>
      <p class="text-xs text-gray-500 mt-1">Click on map to place marker when active.</p>
    </div>

    <div class="flex-1 overflow-auto">
      <h2 class="text-sm font-semibold text-gray-700 mb-2">Active Alerts</h2>
      <ul id="alertList" class="space-y-2 text-sm">
        <!-- alerts injected here -->
      </ul>
    </div>
  </aside>

  <!-- Map Container -->
  <div class="flex-1 relative">
    <div id="map" class="h-full"></div>

    <!-- Zoom Earth Modal -->
    <div id="zoomModal" class="hidden absolute inset-0 bg-black/60 items-center justify-center p-4">
      <div class="bg-white rounded-lg overflow-hidden shadow-2xl w-full max-w-5xl h-[70vh]">
        <div class="flex items-center justify-between p-3 border-b">
          <div class="font-semibold">Zoom Earth — Framed View</div>
          <button id="closeZoom" class="px-3 py-1 text-sm hover:bg-gray-100 rounded">Close</button>
        </div>
        <iframe src="https://zoom.earth/maps/satellite/#view=14.555,121.018,13z" class="w-full h-full" style="border:0" allowfullscreen></iframe>
      </div>
    </div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

  <script>
    const initialCenter = [14.555, 121.018];
    const map = L.map('map').setView(initialCenter, 14);

    const esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });
    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' });
    esriSat.addTo(map);

    const baseLayers = { 'Satellite (Esri)': esriSat, 'OpenStreetMap': osm };
    L.control.layers(baseLayers).addTo(map);

    // UI bindings
    document.getElementById('btnSatellite').addEventListener('click', () => { map.addLayer(esriSat); map.removeLayer(osm); });
    document.getElementById('btnOSM').addEventListener('click', () => { map.addLayer(osm); map.removeLayer(esriSat); });

    const zoomModal = document.getElementById('zoomModal');
    document.getElementById('btnZoomEarth').addEventListener('click', () => { zoomModal.classList.remove('hidden'); zoomModal.classList.add('flex'); });
    document.getElementById('closeZoom').addEventListener('click', () => { zoomModal.classList.add('hidden'); zoomModal.classList.remove('flex'); });

    // Sample alerts
    const alertList = document.getElementById('alertList');
    let markers = [];
    const sampleAlerts = [
      {id:1,title:'Flood Alert',desc:'Minor flooding near Esteban St.',lat:14.556,lng:121.019,level:'Moderate'},
      {id:2,title:'Road Closure',desc:'Road cut due to construction',lat:14.554,lng:121.015,level:'Low'}
    ];

    function renderAlerts(){
      alertList.innerHTML='';
      sampleAlerts.forEach(a=>{
        const li=document.createElement('li');
        li.className='p-2 border rounded bg-gray-50 hover:bg-gray-100 cursor-pointer';
        li.innerHTML=`<div class="flex justify-between"><div><strong>${a.title}</strong><br/><span class="text-xs text-gray-500">${a.desc}</span></div><div class="text-xs text-gray-600">${a.level}</div></div>`;
        li.addEventListener('click',()=>{ map.setView([a.lat,a.lng],16); });
        alertList.appendChild(li);

        const m = L.marker([a.lat,a.lng],{customId:a.id}).addTo(map).bindPopup(`<strong>${a.title}</strong><br/>${a.desc}`);
        markers.push(m);
      });
    }
    renderAlerts();

    // Add marker mode
    let addMarkerMode=false;
    document.getElementById('btnAddMarker').addEventListener('click',()=>{
      addMarkerMode=!addMarkerMode;
      document.getElementById('btnAddMarker').textContent=addMarkerMode?'Click map to place':'Add Marker';
      document.getElementById('btnAddMarker').classList.toggle('bg-green-800');
    });

    map.on('click',function(e){
      if(!addMarkerMode) return;
      const {lat,lng}=e.latlng;
      const m=L.marker([lat,lng]).addTo(map).bindPopup(`<strong>New Report</strong><br/>Lat:${lat.toFixed(5)}<br/>Lng:${lng.toFixed(5)}`).openPopup();
      markers.push(m);
      addMarkerMode=false;
      document.getElementById('btnAddMarker').textContent='Add Marker';
    });

    // Simple search by Lat,Lng
    document.getElementById('btnSearch').addEventListener('click',()=>{
      const val=document.getElementById('searchInput').value.trim();
      const match=val.match(/(-?\d+\.?\d*)[, ]+(-?\d+\.?\d*)/);
      if(match){
        map.setView([parseFloat(match[1]),parseFloat(match[2])],16);
      } else {
        map.setView(initialCenter,15);
      }
    });
  </script>
</body>
</html>
