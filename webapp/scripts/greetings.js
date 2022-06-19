// if time is morning then greetings is Good Morning
// if time is afternoon then greetings is Good Afternoon
// if time is evening then greetings is Good Evening
// if time is night then greetings is Good Night
function greetings() {
    var time = new Date().getHours();
    if (time < 12) {
        return "Good Morning";
    } else if (time >= 12 && time <= 17) {
        return "Good Afternoon";
    } else if (time >= 17 && time <= 20) {
        return "Good Evening";
    } else {
        return "Good Night";
    }
}
// shows the time currently with minutes
function timeNow() {
    var time = new Date().getHours();
    var minutes = new Date().getMinutes();
    var timeNow = time + ":" + minutes;
    return timeNow;
}
