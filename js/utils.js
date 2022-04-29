function idv(a, b) { return (0 | a / b); }

const deep_copy = (item) => JSON.parse(JSON.stringify(item));

function getDateTime(unixtime) {
    var dt = new Date(unixtime);
    var datetime = dt.getFullYear() + "-" +
        (String(dt.getMonth()+101).substr(1,2)) + "-" +
        (String(dt.getDate()+100).substr(1,2)+ " " +
        (String(dt.getHours()+100).substr(1,2))+ ":" +
        (String(dt.getMinutes()+100).substr(1,2))+ ":" +
        (String(dt.getSeconds()+100).substr(1,2)));
    return datetime;
}

