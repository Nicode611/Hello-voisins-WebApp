

document.addEventListener('DOMContentLoaded', function() {
    var hideOnMapBtn = document.querySelector(".hide-on-map");

    hideOnMapBtn.addEventListener('change', function() {
      var xhr = new XMLHttpRequest();
      var baseUrl = '../scripts/settings-scripts/hide-on-map.php';
      var param = hideOnMapBtn.checked ? 'check' : 'uncheck';
      var url = baseUrl + '?' + param;

      xhr.open('GET', url, true);

      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            
        }
      };

      xhr.send();
    });
  });