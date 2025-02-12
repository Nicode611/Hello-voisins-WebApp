function getActualDate() {

    var newDate = new Date();
    var day = newDate.getDate();
    var month = newDate.getMonth() + 1;
    var year = newDate.getFullYear();
    var actualDate = day + '/' + month + '/' + year;

    var hours = newDate.getHours();
    var minutes = newDate.getMinutes();
    var actualHour = hours + ':' + (minutes < 10 ? '0' : '') + minutes;

    return [actualDate, actualHour];
}