function updateTime() {
    var currentTime = new Date();
    var month = fullMonth(currentTime.getMonth()); //January is 0!
    // var day = currentTime.getDay();
    var year = currentTime.getFullYear();
    var day = currentTime.getDate();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    var meridiem = hours >= 12 ? 'PM' : 'AM';

    // Convert hours to 12-hour format
    hours = hours % 12;
    hours = hours ? hours : 12; // Handle midnight (0 hours)

    // Add leading zero if single digit
    hours = (hours < 10 ? "0" : "") + hours;
    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    // Format the time as HH:MM:SS AM/PM
    var timeString = month + " " + day + ", " + year + " | " + hours + ":" + minutes + ":" + seconds + " " + meridiem;

    // Update the time in the HTML element
    document.getElementById("time").innerText = timeString;
}

function fullMonth(monthNo){
    var  months = ['January', 'February', 'March','April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return months[monthNo];
}

// Call updateTime function every second
setInterval(updateTime, 1000);