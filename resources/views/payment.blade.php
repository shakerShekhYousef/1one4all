<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Priority-Payment</title>
    <style>
        /* custom font from google fonts */
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);

        /* Basic styling and positioning of elements */
        body {
            color: #444;
            font-size: 16px;
        }

        #some-box {
            background-image: url('{{asset('storage/images/background.jpg')}}');
            padding: 40px 0px 80px;
            font-family: 'Open Sans', sans-serif;
        }

        #some-box h1 {
            text-align: center;
        }

        #some-box h3 {
            font-size: 26px;
        }

        #some-box a {
            color: #70BCB8;
            text-decoration: none;
            display: block;
            font-weight: bold;
        }

        #some-box a:hover {
            color: #8E7BB2;
        }

        article.row {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 45px 0;
        }

        article.row div {
            width: 49.4%;
            display: inline-block;
            position: relative;
            border: 1px solid white;
        }

        /**** For your reference ****/
        /* article.row {border:1px solid white;}
        article.row:hover {border:1px solid yellow;}
        article.row div:hover {border:1px solid green;} */
        /* Your divs won't expand to contain absolute positioned images */


        #idea-one img, #idea-two img, #idea-three img {
            position: absolute;
            bottom: -20px;
            left: 50px;
        }

        /* Here's the column repositioning magic. This media query says "Apply these styles only until page width is greater than 750px"

        ***To make this work, your media query has to be beneath your other styles to keep it from being overwritten later on by your general styles*** */
        @media (max-width: 750px) {

            /* This resizes the divs that contain your content to fill the width of the page so that they stack vertically */
            article.row div {
                min-width: 300px;
                width: 90%;
                margin: 0 5%;
                text-align: center;
                display: block;
            }

            /* Center your text */
            #some-other-box h3 {
                text-align: center;
            }

            /* Center your image */
            #idea-one img, #idea-two img, #idea-three img {
                position: relative;
                margin: auto;
                left: inherit;
                bottom: inherit;
            }

            /* This tells the browswer to stack the content vertically */
            #idea-two {
                display: -webkit-box;
                display: -moz-box;
                display: box;
                -webkit-box-orient: vertical;
                -moz-box-orient: vertical;
                box-orient: vertical;
            }

            /* This tells the browswer to draw the first box (div:first-of-type) within our stacked content block (identified in the previous rule) in the second position ([...]box-ordinal-group:2;)
            This gives us a nice arrangement of alternating text and images, and allows us two have a custom layout for smaller or wider screens.*/
            #idea-two div:first-of-type {
                -webkit-box-ordinal-group: 2;
                -moz-box-ordinal-group: 2;
                box-ordinal-group: 2;
            }
        }

        .button {
            border-radius: 4px;
            background-color: #b37700;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-weight: bold;
            font-size: small;
            padding: 10px;
            width: 200px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }

        .button span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }

        .button span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }

        .button:hover span {
            padding-right: 25px;
        }

        .button:hover span:after {
            opacity: 1;
            right: 0;
        }


        /*
      If we had 3 boxes, we could draw them in order C, A, B by writing div:first-of-type{[...]group:2;} and div:nth-of-type(2) {[...]group:3;}, and so on.
        */

    </style>

</head>
<body>
<div id="some-box">
    <form action="{{route('payment.paypal')}}" method="post">
        @csrf
        @method("POST")
        <article class="row" id="idea-one">
            <div><img width="150px" src="#"></div>
            <div>
                <p style="font-weight: bold;">Choose Your Payment Method</p>
                <input hidden type="text" id="amount" name="amount" value="{{$amount}}">
                <input hidden type="text" id="request_id" name="request_id" value="{{$request_id}}">
                <input hidden type="text" id="user_id" name="user_id" value="{{$user_id}}">
                <button type="submit" class="button">
                    Pay with Paypal
                </button>
            </div>
        </article>
    </form>

</div>
</body>
</html>
