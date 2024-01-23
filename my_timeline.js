$(() => {

    const ed = $(".follow").attr("data-ed");

    $(".follow").on("click", (e) => {
        let onoff = $(".follow").attr("data-onoff");

        e.preventDefault();
        $.ajax({
            type: "get",
            data: { ed: ed, onoff: onoff },
            url: "system/follow_unfollow.php",
            cache: false,
        }).done((data) => {
            if (data == "success") {
                
                if (onoff == "off") {
                    $(".follow").text("フォロー解除");
                    $('.follow').attr("data-onoff", "on");
                }
                if (onoff == "on") {
                    $(".follow").text("フォローする");
                    $('.follow').attr("data-onoff", "off");
                }

            }
        });
    });

    /*
    const ed2 = $(".unfollow").attr("data-ed");

    $(".unfollow").on("click", (e) => {
        e.preventDefault();
        $.ajax({
            type: "get",
            data: { ed: ed2, follow: "unfollow" },
            url: "system/follow_unfollow.php",
            cache: false,
        }).done((data) => {
            if (data == "success") {
                console.log(data);
                $(".unfollow").text("フォローする");
                $('.unfollow').prop("class", "follow");
            }
        });
    });
    */

})