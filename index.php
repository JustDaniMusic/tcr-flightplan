<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Vlieggids Navlog</title>
    <script>

        function PutThroughLegInfo(legID)
        {
            leg2ID = legID +1;

            var leg1 = document.getElementById('leg'+legID+'Name').value;
            var leg2 = document.getElementById('leg'+leg2ID+'Name').value;


            //change Iframe info
            var iframe = document.getElementById("1_60");
            elem1 = iframe.contentWindow.document.getElementById("naamA");
            elem1.value = leg1;

            iframe.contentWindow.document.getElementById("naamC").value = leg2;
            iframe.contentWindow.document.getElementById("afstandA").innerHTML = leg1;
            iframe.contentWindow.document.getElementById("meetpuntC").innerHTML = leg2;


            iframe.contentWindow.document.getElementById("myCanvas").addEventListener()

        }
    </script>
    <script>
        function addRows() {
            var oRows = document.getElementById('table2').getElementsByTagName('tr');
            var iRowCount = oRows.length;
            var iRowCountLessOne = iRowCount - 1;

            var kleurRoze = (iRowCount % 2 === 0) ? "#f2dcdb" : "#e6b8b7";
            var kleurBlauw = (iRowCount % 2 === 0) ? "#dce6f1" : "#b8cce4";

            var newRow = document.getElementById('table2').insertRow();
            var leg = iRowCountLessOne;

            newRow.innerHTML =
                '<td><input id="'+leg+'_leg" style="background-color: ' + kleurBlauw + '" ' +
                'type="text" value="' + (iRowCount - 1) + '↓" ' +
                'onclick="PutThroughLegInfo('+leg+')"/></td>' +
                '<td><input id="'+leg+'_timeAcc" type="text" style="background-color: ' + kleurRoze + '"/></td>' +
                '<td><input id="'+leg+'_timeInt" type="text" style="background-color: ' + kleurRoze + '"/></td>' +
                '<td><input id="'+leg+'_ETO" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_RETO" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_ATO" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_MEF" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_cruise" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_legName" style="background-color: ' + kleurBlauw + '; width:100%" ' +
                'type="text"/></td>' +
                '<td><input id="'+leg+'_frequentie" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_MH" type="text" style="background-color: ' + kleurRoze + '"/></td>' +
                '<td><input id="'+leg+'_var" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_TH" type="text" style="background-color: ' + kleurRoze + '"/></td>' +
                '<td><input id="'+leg+'_WCA" type="text" style="background-color: ' + kleurRoze + '"/></td>' +
                '<td><input id="'+leg+'_wind" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_windV" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_TT" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_distInt" type="text" style="background-color: ' + kleurBlauw + '"/></td>' +
                '<td><input id="'+leg+'_disAcc" type="text" style="background-color: ' + kleurRoze + '"/></td>' +
                '<td><input id="'+leg+'_GS" type="text" style="background-color: ' + kleurRoze + '"/></td>';
        }
    </script>
    <script>
        function ToggleFrame() {
            var x = document.getElementById("1_60");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>

    <!-- automatisch invullen van rode vakken -->
    <script>
        function rodeVakken() {
            timeInt();
            timeAcc();
            WCA();
            TH();
            MH();
            distAcc();

            document.querySelectorAll('input[id$="_wind"]').forEach(function(windEl) {
                var leg = windEl.id.split('_')[0];
                GS(leg);
            });
        }
        function timeInt() {
            var distInputs = document.querySelectorAll('input[id$="_distInt"]');
            distInputs.forEach(function(el) {
                var leg = el.id.split('_')[0];
                var dist = parseFloat(el.value);
                var gs = parseFloat(document.getElementById(leg + '_GS').value);
                var tiElem = document.getElementById(leg + '_timeInt');
                if (isNaN(dist) || isNaN(gs) || gs === 0) {
                    tiElem.value = '';
                } else {
                    tiElem.value = Math.round((dist / gs) * 60);
                }
            });
        }
        
        function timeAcc() {
            // accumulate time intervals
            var timeIntElems = document.querySelectorAll('input[id$="_timeInt"]');
            var legs = Array.from(timeIntElems).map(function(el) {
                return parseInt(el.id.split('_')[0], 10);
            }).sort(function(a, b) { return a - b; });
            legs.forEach(function(leg) {
                var ti = parseFloat(document.getElementById(leg + '_timeInt').value);
                var taElem = document.getElementById(leg + '_timeAcc');
                if (isNaN(ti)) {
                    taElem.value = '';
                } else {
                    if (leg === legs[0]) {
                        taElem.value = ti;
                    } else {
                        var prev = parseFloat(document.getElementById((leg - 1) + '_timeAcc').value);
                        taElem.value = isNaN(prev) ? ti : prev + ti;
                    }
                }
            });
        }

        function MH() {
            var mhElems = document.querySelectorAll('input[id$="_MH"]');
            mhElems.forEach(function(el) {
                var leg       = el.id.split('_')[0];
                var th        = parseFloat(document.getElementById(leg + '_TH').value);
                var variation = parseFloat(document.getElementById(leg + '_var').value);
                if (isNaN(th) || isNaN(variation)) {
                    el.value = '';
                } else {
                    var rawMh = th - variation;
                    if (rawMh > 359) rawMh -= 360;
                    if (rawMh <   0) rawMh += 360;
                    el.value = Math.round(rawMh);
                }
            });
        }

        function TH() {
            var thElems = document.querySelectorAll('input[id$="_TH"]');
            thElems.forEach(function(el) {
                var leg = el.id.split('_')[0];
                var tt  = parseFloat(document.getElementById(leg + '_TT').value);
                var wca = parseFloat(document.getElementById(leg + '_WCA').value);
                if (isNaN(tt) || isNaN(wca)) {
                    el.value = '';
                } else {
                    var th = tt + wca;
                    if (th > 359) th -= 360;
                    if (th <   0) th += 360;
                    el.value = Math.round(th);
                }
            });
        }

        function WCA() {
        const RAD = Math.PI/180;
        document.querySelectorAll('input[id$="_WCA"]').forEach(el => {
            const leg   = el.id.split('_')[0];
            const wind  = parseFloat(document.getElementById(leg + '_wind').value);
            const windV = parseFloat(document.getElementById(leg + '_windV').value);
            const tt    = parseFloat(document.getElementById(leg + '_TT').value);
            const tas   = parseFloat(document.getElementById('TAS').value);

            if ([wind, windV, tt, tas].some(v => isNaN(v) || tas === 0)) {
            el.value = '';
            return;
            }

            // no “–180” here
            const raw = (windV / tas) * Math.sin((wind - tt) * RAD);

            if (raw < -1 || raw > 1) {
            el.value = '';  // out of range
            } else {
            el.value = Math.round(Math.asin(raw) * 180/Math.PI);
            }
        });
        }

        function distAcc() {
            var distElems = document.querySelectorAll('input[id$="_distInt"]');
            var legs = Array.from(distElems)
                .map(el => parseInt(el.id.split('_')[0], 10))
                .sort((a,b)=>a-b);
            legs.forEach(function(leg) {
                var di    = parseFloat(document.getElementById(leg + '_distInt').value);
                var daElem= document.getElementById(leg + '_disAcc');
                if (isNaN(di) || di === 0) {
                    daElem.value = '';
                } else {
                    var acc = (leg === legs[0])
                        ? di
                        : parseFloat(document.getElementById((leg-1) + '_disAcc').value) + di;
                    daElem.value = Math.round(acc);
                }
            });
        }
        
        function GS(leg) {
            const toRad = deg => deg * Math.PI/180;
            const wind   = parseFloat(document.getElementById(leg + '_wind').value);
            const windV  = parseFloat(document.getElementById(leg + '_windV').value);
            const TT     = parseFloat(document.getElementById(leg + '_TT').value);
            const TAS    = parseFloat(document.getElementById('TAS').value);
            const WCA    = parseFloat(document.getElementById(leg + '_WCA').value);

            if ([wind, windV, TT, TAS, WCA].some(v => isNaN(v)) || TAS === 0) {
                alert ('Niet alle velden zijn ingevuld!')
                document.getElementById(leg + '_GS').value = '';
                return;
            }

            let gs;
            const diff = wind - TT;
            if (diff === 0 || Math.abs(diff) === 360) {
                gs = TAS - windV;
            } else if (Math.abs(diff) === 180) {
                gs = TAS + windV;
            } else if ((diff > 180 && diff < 360) || (diff < 0 && diff > -180)) {
                gs = TAS * Math.sin(toRad(-diff - Math.abs(WCA))) / Math.sin(toRad(-diff));
            } else {
                gs = TAS * Math.sin(toRad(diff - Math.abs(WCA))) / Math.sin(toRad(diff));
            }

            document.getElementById(leg + '_GS').value = Math.ceil(gs);
        }
    </script>


    <style>
        /* Micro Reset */

        html {
            font-size: 100%;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            box-sizing: border-box;
        }

        *, *:before, *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 80%;
        }

        footer
        {
            font-size: 10px;
            font-weight: lighter;
        }


        img {
            max-width: 100%;
        }

        /* Typography */

        body {
            line-height: 1.75;
        }

        h1, h2, h3, h4, h5, h6 {
            padding: 0;
            margin: 48px 0 16px;
            line-height: 1.25;
            text-align: center;
        }

        h1 {
            font-size: 32px;
        }

        h2 {
            font-size: 22px;
            font-weight: bold;
        }

        h3, h4, h5, h6 {
            font-size: 19px;
            font-weight: bold;
            text-align: left;
        }

        blockquote {
            margin: 1em 0;
            padding: 0 2em;
            border-left: 3px solid #ddd;
        }

        /* Code */

        pre, code {
            font-size: .9em;
        }

        pre code {
            display: block;
            border: 1px solid #ddd;
            box-shadow: 5px 5px 5px #eee;
            padding: 1em;
            overflow-x: auto;
            line-height: 1.75;
        }

        code {
            background: #f9f9f9;
        }

        pre code {
            background: none;
        }

        /* Lists */

        ul, ol {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        li {
            margin: 4px 0;
            padding: 0;
        }

        ul li a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }

        ul li a:hover {
            background-color: #f0f0f0;
        }


        /* Links */

        a {
            color: black;
            text-decoration: none;
            border-bottom: 1px solid;
        }

        a:hover {
        }

        sup a {
            border-bottom: none;
        }

        /* Miscellanea */

        hr {
            display: block;
            height: 1px;
            border: 0;
            border-top: 1px solid;
            margin: 50px auto;
            padding: 0;
            max-width: 300px;
        }

        .copyright {
            text-align: center;
        }

        .post-nav {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }

        .nav-next {
            margin-left: 1em;
            text-align: right;
        }

        .nav-prev {
            margin-right: 1em;
        }

        .comments {
            margin-top: 20px;
        }

        /* Layout */

        body {
            width: 100%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            padding: 5px;
            font-size: medium;
        }

        .masthead {
            width: 200px;
            padding: 20px 50px 20px 10px;
            float: left;
        }

        .main {
            width: 80%;
            padding: 20px 20px 20px 20px;
            margin-left: 200px;
            border-left: 3px solid black;
            min-height: calc(100vh - 60px);
        }

        /* Masthead */

        .masthead h1 {
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
            text-align: right;
            font-size: 46px;
            line-height: 58px;
            font-weight: 300;
        }

        .masthead h1 a {
            border-bottom: none;
        }

        .masthead .tagline {
            margin-top: 0;
            text-align: right;
            color: #666;
        }

        .masthead .menu {
            margin-right: 20px;
            direction: rtl;
            width: 180px;
        }

        .masthead .menu a {
            direction: ltr;
        }

        .masthead .menu ul ul {
            list-style: none;
            margin-left: 10px;
            margin-right: 10px;
        }

        .masthead .menu li li::before {
            content: "•\00a\000a0\00a0"
        }

        /* Main */

        .main .title h1 {
            margin-top: 0;
            margin-bottom: 40px;
            font-weight: bold;
        }

        .title h3 {
            font-weight: normal;
            text-align: center;
        }

        .subtitle {
            font-size: .9em;
            color: #666;
        }

        /* Footnotes */

        .footnotes {
            font-size: .9em;
        }

        /* Tables */


        table {
            margin-top: 25px;
            border-spacing: 0;
            width: 1250px;
            border: 2px solid;
        }

        table tr {
            padding: 0;

        }

        table > tbody > tr > td {
            border-bottom: 1px dashed darkgrey;
            border-right: 1px dashed darkgrey;
            text-align: center;
            background-color: #4f81bd;
            color: white;
            padding: 0;
        }

        #table2 tbody tr td {

        }


        #table1 input {
            border: 0;
            margin: 0 auto;
            width: 107px;
            height: 40px;
            background-color: #dce6f1;
            font-size: medium;
        }

        #table2 input {
            border: 0;
            margin: 0 auto;
            width: 55px;
            height: 40px;
            background-color: #dce6f1;
            font-size: medium;
        }

        #table2 tbody tr th {
            background-color: #4f81bd;
            font-size: x-large;
            padding-bottom: 10px;
            padding-top: 10px;
            border-right: 1px black solid;
            color: white;
        }


        /* A few custom styles for date inputs */
        input[type="date"], input[type="time"], input[type="number"] {
            font-family: Arial, sans-serif;
            appearance: none;
            -webkit-appearance: none;
            color: black;
            font-size: medium;
            background-color: #dce6f1;
            padding-left: 5px;
            display: inline-block !important;
            visibility: visible !important;
        }

        input[type="text"] {
            padding-left: 10px;
        }

        button, select {
            background-color: #9AA6B2;
            font-size: medium;
            padding-bottom: 10px;
            padding-top: 10px;
            margin-top: 25px;
            color: midnightblue;
            width: 20%;
        }


        .home .tils,
        .home .links,
        .home .posts {
            margin-bottom: 48px;
        }

        /**
         * Utility Styles
         */

        .unselectable {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /**
         * -------------------------------------------------------------------------
         *  Media Queries
         * -------------------------------------------------------------------------
         *
         * The @viewport tag does the same thing as
         *
         *   <meta name="viewport" content="width=device-width">
         *
         * but in the future W3C standard way. The -ms- prefix is required for
         * IE10+ to render responsive styling in Windows 8 "snapped" views;
         * IE10+ does not honour the meta tag.
         */

        @-ms-viewport {
            width: device-width;
        }

        @viewport {
            width: device-width;
        }

        /* Tablet screens and smaller */

        @media screen and (max-width: 1280px) {

            body {
                width: auto;
            }

            h1, h2, h3, h4, h5, h6 {
                margin-top: 24px;
            }

            .masthead {
                width: auto;
                float: none;
                padding: 20px 0 20px;
                margin-left: 10px;
                margin-right: 10px;
            }

            .main {
                width: auto;
                padding: 20px 10px;
                margin-left: 0;
                border-left: none;
                min-height: auto;
            }

            .masthead h1 {
                text-align: left;
                font-size: 2.4rem;
            }

            .masthead .tagline {
                text-align: left;
            }

            .masthead .menu {
                direction: ltr;
                margin-right: 0;
            }

            .masthead .menu ul {
                text-align: left;
                margin: 0;
                padding: 0;
                flex-wrap: wrap;
                gap: 4px 20px;
                max-width: 440px;
                font-size: 14px;
                display: grid;
                grid-template-columns: auto auto auto auto;
            }

            .masthead .menu li {
                list-style: none;
                margin: 0;
                padding: 0;
                white-space: nowrap;
            }

            .title h1 {
                text-align: left;
            }

            hr {
                max-width: none;
            }
        }

        /* Landscape phone screens and smaller */

        @media screen and (max-width: 720px) {
        }

        /* Portrait phone screens */

        @media screen and (max-width: 480px) {

            body {
                font-size: 16px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 18px;
            }

            h3, h4, h5, h6 {
                font-size: 16px;
            }

            pre, code {
                font-size: 12px;
            }

            .masthead {
                padding-top: 0;
            }

        }
    </style>

</head>


<body class="contact">
<header class="masthead">

    <nav class="menu">
        <ul>
            <li><a href="#" onclick="addRows()">Nieuwe leg</a></li>
            <li><a href="#" onclick="rodeVakken()">Invullen rood</a></li>
            <li><a href="#" onclick="ToggleFrame()">Weergeven 1:60</a></li>
        </ul>
    </nav>

</header>

<article class="main">
    <header class="title">
        <h1>Navigatielog</h1>
    </header>


    <table id="table1">
        <tr>
            <td>Date</td>
            <td colspan="3"><input style="width: 100%; margin: 0 auto" type="date"/></td>
            <td>Tacho_beg:</td>
            <td><input type="text"/></td>
            <td>Tacho_end:</td>
            <td><input type="text"/></td>
            <td>Pilot</td>
            <td><input type="text"/></td>
            <td>Altitudes</td>
            <td style="width:50px">OAT</td>
            <td>IAS</td>
            <td>TAS</td>
        </tr>
        <tr>
            <td>Dept</td>
            <td><input type="text"/></td>
            <td>elev:</td>
            <td><input type="text"/></td>
            <td>Off-blocks:</td>
            <td><input type="text"/></td>
            <td>Engine_off</td>
            <td><input type="text"/></td>
            <td>Acft_type</td>
            <td><input type="text"/></td>
            <td><input type="number"/></td>
            <td><input style="width:50px" type="number"/></td>
            <td><input type="number"/></td>
            <td><input id="TAS" type="number"/></td>
        </tr>
        <tr>
            <td>Dest</td>
            <td><input type="text"/></td>
            <td>elev:</td>
            <td><input type="text"/></td>
            <td>Take-off time:</td>
            <td><input type="time" style="width: 99%"/></td>
            <td>Landing-time</td>
            <td><input type="time" style="width: 99%"/></td>
            <td>Reg</td>
            <td><input type="text" value="PH-"/></td>
            <td><input type="number"/></td>
            <td><input style="width:50px" type="number"/></td>
            <td><input type="number"/></td>
            <td><input type="number"/></td>
        </tr>


    </table>
    <table id="table2">
        <tr style="font-size: x-large;">
            <th>Leg</th>
            <th colspan="2">Time</th>
            <th colspan="3">Schedule</th>
            <th colspan="2">Alt/FL</th>
            <th colspan="2">Checkpoints</th>
            <th colspan="4">Headings</th>
            <th colspan="2">Wind</th>
            <th>Dir.</th>
            <th colspan="2">Dist.</th>
            <th>Spd</th>
        </tr>
        <tr>
            <td>no.</td>
            <td>Acc.</td>
            <td>Int.</td>
            <td>ETO</td>
            <td>RETO</td>
            <td>ATO</td>
            <td>MEF</td>
            <td>Cruise</td>
            <td>__Checkpoint__</td>
            <td>Freq.</td>
            <td>MH</td>
            <td>var.</td>
            <td>TH</td>
            <td>WCA</td>
            <td>w</td>
            <td>V</td>
            <td>TT</td>
            <td>Int.</td>
            <td>Acc</td>
            <td>GS</td>
        </tr>
        <tr>
            <td><input type="text" value="1 &darr;" onclick="PutThroughLegInfo(1)" /></td>
            <td><input id="1_timeAcc" type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="1_timeInt" type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="1_ETO" type="text"/></td>
            <td><input id="1_RETO" type="text"/></td>
            <td><input id="1_ATO" type="text"/></td>
            <td><input id="1_MEF" type="text"/></td>
            <td><input id="1_cruise" type="text"/></td>
            <td><input id="1_leg1Name" style="width: 100%" type="text"/></td>
            <td><input id="1_frequentie" type="text"/></td>
            <td><input id="1_MH" type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="1_var" type="text"/></td>
            <td><input id="1_TH" type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="1_WCA" type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="1_wind" type="text"/></td>
            <td><input id="1_windV" type="text"/></td>
            <td><input id="1_TT" type="text"/></td>
            <td><input id="1_distInt" type="text"/></td>
            <td><input id="1_disAcc" type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="1_GS" type="text" style="background-color: #f2dcdb"/></td>
        </tr>
        <tr>
            <td>
                <input
                id="2_leg"
                type="text"
                style="background-color: #b8cce4"
                value="2 ↓"
                onclick="PutThroughLegInfo(2)"
                />
            </td>
            <td><input id="2_timeAcc"  type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="2_timeInt"  type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="2_ETO"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_RETO"     type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_ATO"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_MEF"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_cruise"   type="text" style="background-color: #b8cce4"/></td>
            <td>
                <input
                id="2_legName"
                type="text"
                style="background-color: #b8cce4; width:100%"
                />
            </td>
            <td><input id="2_frequentie" type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_MH"        type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="2_var"       type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_TH"        type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="2_WCA"       type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="2_wind"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_windV"     type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_TT"        type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_distInt"   type="text" style="background-color: #b8cce4"/></td>
            <td><input id="2_disAcc"    type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="2_GS"        type="text" style="background-color: #e6b8b7"/></td>
        </tr>
        <tr>
            <td>
                <input
                id="3_leg"
                type="text"
                style="background-color: ..."
                value="3 ↓"
                onclick="PutThroughLegInfo(3)"
                />
            </td>
            <td><input id="3_timeAcc"  type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="3_timeInt"  type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="3_ETO"      type="text"/></td>
            <td><input id="3_RETO"     type="text"/></td>
            <td><input id="3_ATO"      type="text"/></td>
            <td><input id="3_MEF"      type="text"/></td>
            <td><input id="3_cruise"   type="text"/></td>
            <td>
                <input
                id="3_legName"
                type="text"
                style="width:100%"
                />
            </td>
            <td><input id="3_frequentie" type="text"/></td>
            <td><input id="3_MH"        type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="3_var"       type="text"/></td>
            <td><input id="3_TH"        type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="3_WCA"       type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="3_wind"      type="text"/></td>
            <td><input id="3_windV"     type="text"/></td>
            <td><input id="3_TT"        type="text"/></td>
            <td><input id="3_distInt"   type="text"/></td>
            <td><input id="3_disAcc"    type="text" style="background-color: #f2dcdb"/></td>
            <td><input id="3_GS"        type="text" style="background-color: #f2dcdb"/></td>
        </tr>
        <tr>
            <td>
                <input
                id="4_leg"
                type="text"
                style="background-color: #b8cce4"
                value="4 ↓"
                onclick="PutThroughLegInfo(4)"
                />
            </td>
            <td><input id="4_timeAcc"  type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="4_timeInt"  type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="4_ETO"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_RETO"     type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_ATO"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_MEF"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_cruise"   type="text" style="background-color: #b8cce4"/></td>
            <td>
                <input
                id="4_legName"
                type="text"
                style="background-color: #b8cce4; width:100%"
                />
            </td>
            <td><input id="4_frequentie" type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_MH"        type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="4_var"       type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_TH"        type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="4_WCA"       type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="4_wind"      type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_windV"     type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_TT"        type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_distInt"   type="text" style="background-color: #b8cce4"/></td>
            <td><input id="4_disAcc"    type="text" style="background-color: #e6b8b7"/></td>
            <td><input id="4_GS"        type="text" style="background-color: #e6b8b7"/></td>
        </tr>
    </table>
    <table id="table2" style="margin-top: 0px; border-top: 0">
        <tr>
            <td><input type="text" /></td>
            <td><input type="text" style="background-color: #f2dcdb"/></td>
            <td><input type="text" style="background-color: #f2dcdb"/></td>
            <td><input type="text"/></td>
            <td><input type="text"/></td>
            <td><input type="text"/></td>
            <td><input type="text"/></td>
            <td><input type="text"/></td>
            <td><input style="width: 100%" type="text" value="ALTERNATE"/></td>
            <td><input type="text"/></td>
            <td><input type="text" style="background-color: #f2dcdb"/></td>
            <td><input type="text"/></td>
            <td><input type="text" style="background-color: #f2dcdb"/></td>
            <td><input type="text" style="background-color: #f2dcdb"/></td>
            <td><input type="text"/></td>
            <td><input type="text"/></td>
            <td><input type="text"/></td>
            <td><input type="text"/></td>
            <td><input type="text" style="background-color: #f2dcdb"/></td>
            <td><input type="text" style="background-color: #f2dcdb"/></td>
        </tr>
        <tr>
            <td><input type="text" style="background-color: #b8cce4" /></td>
            <td><input type="text" style="background-color: #e6b8b7"/></td>
            <td><input type="text" style="background-color: #e6b8b7"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input style="background-color: #b8cce4; width: 100%" type="text"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #e6b8b7"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #e6b8b7"/></td>
            <td><input type="text" style="background-color: #e6b8b7"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #b8cce4"/></td>
            <td><input type="text" style="background-color: #e6b8b7"/></td>
            <td><input type="text" style="background-color: #e6b8b7"/></td>
        </tr>
    </table>


    <iframe style="display: none" id="1_60" src="../1_60/index.php" width="1250px" height="900px" frameborder="0" scrolling="no"></iframe>

    <footer>
        Ontwikkeld door: Vinod / Getest door Frank / Afgerond door Dani
    </footer>
</article>
</body>
</html>