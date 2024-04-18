$(document).ready(function () {
    //Pusher
    var pusher = new Pusher(PUSHER_KEY, {
        cluster: PUSHER_CLUSTER
    });

    // channel color_Status
    var channel = pusher.subscribe('color_Status');

    channel.bind("event", (data) => {

        var pusher_Color = "";

        switch (data.event) {

            case "status_Blue":
                pusher_Color = "blue";
                break;

            case "status_Yellow":
                pusher_Color = "yellow";
                break;

            case "status_Red":
                pusher_Color = "red";
                break;

            case "status_Green":
                pusher_Color = "green";
                break;

            case "status_Orange":
                pusher_Color = "orange";
                break;

            default:
                pusher_Color = 'black';
                ;
        }

        iziToast.show({
            image: data.img,
            imageWidth: 50,
            position: 'topRight',
            close: true,
            theme: 'light',
            message: data.title,
            color: pusher_Color // blue, red, green, yellow
        });
    });
})