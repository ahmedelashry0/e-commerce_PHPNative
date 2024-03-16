$(function () {
    "use strict";
    // Switch Between Login & Signup

    $('.login-page h1 span').click(function () {

        $(this).addClass('selected').siblings().removeClass('selected');

        $('.login-page form').hide();

        $('.' + $(this).data('class')).fadeIn(100);

    });


    // Trigger The Selectboxit

    $("select").selectBoxIt({
        autoWidth: false,
        theme: "bootstrap",
    });

    // Hide Placeholder On Form Focus

    $("[placeholder]")
        .focus(function () {
            $(this).attr("data-text", $(this).attr("placeholder"));

            $(this).attr("placeholder", "");
        })
        .blur(function () {
            $(this).attr("placeholder", $(this).attr("data-text"));
        });

    //Add asterisk to required fields
    $("input").each(function () {
        if ($(this).attr("required") === "required") {
            $(this).after("<span class='asterisk'>*</span>");
        }
    });
    //Convert password field to text field on hover
    var passField = $(".password");
    $(".show-pass").hover(
        function () {
            passField.attr("type", "text");
        },
        function () {
            passField.attr("type", "password");
        }
    );

    //Categorie view option
    $(".confirm").click(function () {
        return confirm("Are you sure");
    });


    $('.live').keyup(function () {

        $($(this).data('class')).text($(this).val());

    });
});
