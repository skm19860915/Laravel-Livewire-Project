import axios from "axios";

var helper = {

  getSiteUrl: function () {
    return window.location.origin;
  },

  loadStates: function () {
    let statesJsonFile = `${this.getSiteUrl()}/storage/states.json`;
    $.getJSON(statesJsonFile, function (data) {
      let items = [];
      $.each(data, function (key, row) {
        let selected = '';
        if ($('#state').data('value') == row.state_code) {
          selected = 'selected';
        }
        items.push(`<option value="${row.state_code}" ${selected}>${row.state_name}</option>`);
      });

      let html = items.join("");
      $('#state').append(html);
    });
  },

  isSafariBrowser: function () {
    let isSafariBrowser = false;
    let chromeAgent = navigator.userAgent.indexOf("Chrome") > -1;
    let safariAgent = navigator.userAgent.indexOf("Safari") > -1;
    if (!chromeAgent && safariAgent) {
      isSafariBrowser = true;
    }

    return isSafariBrowser;
  },

}

export default helper;
