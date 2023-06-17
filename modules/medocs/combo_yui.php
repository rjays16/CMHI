<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Query a JavaScript Array for In-memory Data</title>

</head>

<style type="text/css">
body {
	margin:0;
	padding:0;
}

/* custom styles for multiple stacked instances */
/* custom styles for scrolling container */
#statesautocomplete,
#statesautocomplete2 {
    width:15em; /* set width here */
    padding-bottom:2em;
    height:12em; /* define height for container to appear inline */
}
#statesautocomplete {
    z-index:9000; /* z-index needed on top instance for ie & sf absolute inside relative issue */
}
#statesinput,
#statesinput2 {
    _position:absolute; /* abs pos needed for ie quirks */
}
/* custom styles for scrolling container */
#statescontainer .yui-ac-content {
    max-height:11em;overflow:auto;overflow-x:hidden; /* scrolling */
    _height:11em;  /* ie6 */ 
	  position:absolute
}


</style>

<!--
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.1/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.1/build/autocomplete/assets/skins/sam/autocomplete.css" />
<script type="text/javascript" src="http://yui.yahooapis.com/2.5.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.5.1/build/animation/animation-min.js"></script>

<script type="text/javascript" src="http://yui.yahooapis.com/2.5.1/build/autocomplete/autocomplete-min.js"></script>
-->
<link rel="stylesheet" type="text/css" href="../../../js/yui-2.3/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="../../../js/yui-2.3/build/autocomplete/assets/skins/sam/autocomplete.css" />
<script type="text/javascript" src="../../../js/yui-2.3/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="../../../js/yui-2.3/build/animation/animation-min.js"></script>

<script type="text/javascript" src="../../../js/yui-2.3/build/autocomplete/autocomplete-min.js"></script>

<body class=" yui-skin-sam">

<h1>Query a JavaScript Array for In-memory Data</h1>

<!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->

<h3>Find a state:</h3>
<div id="statesautocomplete">
	<input id="statesinput" type="text">

	<div id="statescontainer"></div>
</div>
<h3>Find an area code</h3>
<div id="statesautocomplete2">
	<input id="statesinput2" type="text">
	<div id="statescontainer2"></div>
</div>

<!-- In-memory JS array begins-->
<script type="text/javascript">
YAHOO.example.statesArray = [
    "Alabama",
    "Alaska",
    "Arizona",
    "Arkansas",
    "California",
    "Colorado",
    "Connecticut",
    "Delaware",
    "Florida",
    "Georgia",
    "Hawaii",
    "Idaho",
    "Illinois",
    "Indiana",
    "Iowa",
    "Kansas",
    "Kentucky",
    "Louisiana",
    "Maine",
    "Maryland",
    "Massachusetts",
    "Michigan",
    "Minnesota",
    "Mississippi",
    "Missouri",
    "Montana",
    "Nebraska",
    "Nevada",
    "New Hampshire",
    "New Jersey",
    "New Mexico",
    "New York",
    "North Dakota",
    "North Carolina",
    "Ohio",
    "Oklahoma",
    "Oregon",
    "Pennsylvania",
    "Rhode Island",
    "South Carolina",
    "South Dakota",
    "Tennessee",
    "Texas",
    "Utah",
    "Vermont",
    "Virginia",
    "Washington",
    "West Virginia",
    "Wisconsin",
    "Wyoming"
];

YAHOO.example.areacodesArray = [
    ["201", "New Jersey"],
    ["202", "Washington, DC"],
    ["203", "Connecticut"],
    ["204", "Manitoba, Canada"],
    ["205", "Alabama"],
    ["206", "Washington"],
    ["207", "Maine"],

    ["208", "Idaho"],
    ["209", "California"],
    ["210", "Texas"],
    ["212", "New York"],
    ["213", "California"],
    ["214", "Texas"],

    ["215", "Pennsylvania"],
    ["216", "Ohio"],
    ["217", "Illinois"],
    ["218", "Minnesota"],
    ["219", "Indiana"],
    ["224", "Illinois"],

    ["225", "Louisiana"],
    ["227", "Maryland"],
    ["228", "Mississippi"],
    ["229", "Georgia"],
    ["231", "Michigan"],
    ["234", "Ohio"],

    ["239", "Florida"],
    ["240", "Maryland"],
    ["242", "Bahamas"],
    ["246", "Barbados"],
    ["248", "Michigan"],
    ["250", "British Columbia"],

    ["251", "Alabama"],
    ["252", "North Carolina"],
    ["253", "Washington"],
    ["254", "Texas"],
    ["256", "Alabama"],
    ["260", "Indiana"],

    ["262", "Wisconsin"],
    ["264", "Anguilla"],
    ["267", "Pennsylvania"],
    ["268", "Antigua and Barbuda"],
    ["269", "Michigan"],
    ["270", "Kentucky"],

    ["276", "Virginia"],
    ["281", "Texas"],
    ["283", "Ohio"],
    ["284", "British Virgin Islands"],
    ["289", "Ontario"],
    ["301", "Maryland"],

    ["302", "Delaware"],
    ["303", "Colorado"],
    ["304", "West Virginia"],
    ["305", "Florida"],
    ["306", "Saskatchewan, Canada"],
    ["307", "Wyoming"],

    ["308", "Nebraska"],
    ["309", "Illinois"],
    ["310", "California"],
    ["312", "Illinois"],
    ["313", "Michigan"],
    ["314", "Missouri"],

    ["315", "New York"],
    ["316", "Kansas"],
    ["317", "Indiana"],
    ["318", "Louisiana"],
    ["319", "Iowa"],
    ["320", "Minnesota"],

    ["321", "Florida"],
    ["323", "California"],
    ["330", "Ohio"],
    ["331", "Illinois"],
    ["334", "Alabama"],
    ["336", "North Carolina"],

    ["337", "Louisiana"],
    ["339", "Massachusetts"],
    ["340", "US Virgin Islands"],
    ["345", "Cayman Islands"],
    ["347", "New York"],
    ["351", "Massachusetts"],

    ["352", "Florida"],
    ["360", "Washington"],
    ["361", "Texas"],
    ["386", "Florida"],
    ["401", "Rhode Island"],
    ["402", "Nebraska"],

    ["403", "Alberta, Canada"],
    ["404", "Georgia"],
    ["405", "Oklahoma"],
    ["406", "Montana"],
    ["407", "Florida"],
    ["408", "California"],

    ["409", "Texas"],
    ["410", "Maryland"],
    ["412", "Pennsylvania"],
    ["413", "Massachusetts"],
    ["414", "Wisconsin"],
    ["415", "California"],

    ["416", "Ontario, Canada"],
    ["417", "Missouri"],
    ["418", "Quebec, Canada"],
    ["419", "Ohio"],
    ["423", "Tennessee"],
    ["424", "California"],

    ["425", "Washington"],
    ["434", "Virginia"],
    ["435", "Utah"],
    ["440", "Ohio"],
    ["441", "Bermuda"],
    ["443", "Maryland"],

    ["445", "Pennsylvania"],
    ["450", "Quebec, Canada"],
    ["464", "Illinois"],
    ["469", "Texas"],
    ["470", "Georgia"],
    ["473", "Grenada"],

    ["475", "Connecticut"],
    ["478", "Georgia"],
    ["479", "Arkansas"],
    ["480", "Arizona"],
    ["484", "Pennsylvania"],
    ["501", "Arkansas"],

    ["502", "Kentucky"],
    ["503", "Oregon"],
    ["504", "Louisiana"],
    ["505", "New Mexico"],
    ["506", "New Brunswick, Canada"],
    ["507", "Minnesota"],

    ["508", "Massachusetts"],
    ["509", "Washington"],
    ["510", "California"],
    ["512", "Texas"],
    ["513", "Ohio"],
    ["514", "Quebec, Canada"],

    ["515", "Iowa"],
    ["516", "New York"],
    ["517", "Michigan"],
    ["518", "New York"],
    ["519", "Ontario, Canada"],
    ["520", "Arizona"],

    ["530", "California"],
    ["540", "Virginia"],
    ["541", "Oregon"],
    ["551", "New Jersey"],
    ["557", "Missouri"],
    ["559", "California"],

    ["561", "Florida"],
    ["562", "California"],
    ["563", "Iowa"],
    ["564", "Washington"],
    ["567", "Ohio"],
    ["570", "Pennsylvania"],

    ["571", "Virginia"],
    ["573", "Missouri"],
    ["574", "Indiana"],
    ["580", "Oklahoma"],
    ["585", "New York"],
    ["586", "Michigan"],

    ["601", "Mississippi"],
    ["602", "Arizona"],
    ["603", "New Hampshire"],
    ["604", "British Columbia, Canada"],
    ["605", "South Dakota"],
    ["606", "Kentucky"],

    ["607", "New York"],
    ["608", "Wisconsin"],
    ["609", "New Jersey"],
    ["610", "Pennsylvania"],
    ["612", "Minnesota"],
    ["613", "Ontario, Canada"],

    ["614", "Ohio"],
    ["615", "Tennessee"],
    ["616", "Michigan"],
    ["617", "Massachusetts"],
    ["618", "Illinois"],
    ["619", "California"],

    ["620", "Kansas"],
    ["623", "Arizona"],
    ["626", "California"],
    ["630", "Illinois"],
    ["631", "New York"],
    ["636", "Missouri"],

    ["641", "Iowa"],
    ["646", "New York"],
    ["647", "Ontario, Canada"],
    ["649", "Turks and Caicos Islands"],
    ["650", "California"],
    ["651", "Minnesota"],

    ["660", "Missouri"],
    ["661", "California"],
    ["662", "Mississippi"],
    ["664", "Montserrat"],
    ["667", "Maryland"],
    ["670", "CNMI"],

    ["671", "Guam"],
    ["678", "Georgia"],
    ["682", "Texas"],
    ["701", "North Dakota"],
    ["702", "Nevada"],
    ["703", "Virginia"],

    ["704", "North Carolina"],
    ["705", "Ontario, Canada"],
    ["706", "Georgia"],
    ["707", "California"],
    ["708", "Illinois"],
    ["709", "Newfoundland, Canada"],

    ["712", "Iowa"],
    ["713", "Texas"],
    ["714", "California"],
    ["715", "Wisconsin"],
    ["716", "New York"],
    ["717", "Pennsylvania"],

    ["718", "New York"],
    ["719", "Colorado"],
    ["720", "Colorado"],
    ["724", "Pennsylvania"],
    ["727", "Florida"],
    ["731", "Tennessee"],

    ["732", "New Jersey"],
    ["734", "Michigan"],
    ["737", "Texas"],
    ["740", "Ohio"],
    ["754", "Florida"],
    ["757", "Viriginia"],

    ["758", "St. Lucia"],
    ["760", "California"],
    ["763", "Minnesota"],
    ["765", "Indiana"],
    ["767", "Dominica"],
    ["770", "Georgia"],

    ["772", "Florida"],
    ["773", "Illinois"],
    ["774", "Massachusetts"],
    ["775", "Nevada"],
    ["778", "British Columbia, Canada"],
    ["780", "Alberta, Canada"],

    ["781", "Massachusetts"],
    ["784", "St. Vincent &amp; Gren."],
    ["785", "Kansas"],
    ["786", "Florida"],
    ["787", "Puerto Rico"],

    ["801", "Utah"],
    ["802", "Vermont"],
    ["803", "South Carolina"],
    ["804", "Virginia"],
    ["805", "California"],
    ["806", "Texas"],

    ["807", "Ontario, Canada"],
    ["808", "Hawaii"],
    ["809", "Dominican Republic"],
    ["810", "Michigan"],
    ["812", "Indiana"],
    ["813", "Florida"],

    ["814", "Pennsylvania"],
    ["815", "Illinois"],
    ["816", "Missouri"],
    ["817", "Texas"],
    ["818", "California"],
    ["819", "Quebec, Canada"],

    ["828", "North Carolina"],
    ["830", "Texas"],
    ["831", "California"],
    ["832", "Texas"],
    ["835", "Pennsylvania"],
    ["843", "South Carolina"],

    ["845", "New York"],
    ["847", "Illinois"],
    ["848", "New Jersey"],
    ["850", "Florida"],
    ["856", "New Jersey"],
    ["857", "Massachusetts"],

    ["858", "California"],
    ["859", "Kentucky"],
    ["860", "Connecticut"],
    ["862", "New Jersey"],
    ["863", "Florida"],
    ["864", "South Carolina"],

    ["865", "Tennessee"],
    ["867", "Yukon, NW Territories, Canada"],
    ["868", "Trinidad and Tobago"],
    ["869", "St. Kitts &amp; Nevis"],
    ["870", "Arkansas"],

    ["872", "Illinois"],
    ["876", "Jamaica"],
    ["878", "Pennsylvania"],
    ["901", "Tennessee"],
    ["902", "Nova Scotia, Canada"],
    ["903", "Texas"],

    ["904", "Florida"],
    ["905", "Ontario, Canada"],
    ["906", "Michigan"],
    ["907", "Alaska"],
    ["908", "New Jersey"],
    ["909", "California"],

    ["910", "North Carolina"],
    ["912", "Georgia"],
    ["913", "Kansas"],
    ["914", "New York"],
    ["915", "Texas"],
    ["916", "California"],

    ["917", "New York"],
    ["918", "Oklahoma"],
    ["919", "North Carolina"],
    ["920", "Wisconsin"],
    ["925", "California"],
    ["928", "Arizona"],

    ["931", "Tennessee"],
    ["936", "Texas"],
    ["937", "Ohio"],
    ["939", "Puerto Rico"],
    ["940", "Texas"],
    ["941", "Florida"],

    ["947", "Michigan"],
    ["949", "California"],
    ["952", "Minnesota"],
    ["954", "Florida"],
    ["956", "Texas"],
    ["959", "Connecticut"],

    ["970", "Colorado"],
    ["971", "Oregon"],
    ["972", "Texas"],
    ["973", "New Jersey"],
    ["975", "Missouri"],
    ["978", "Massachusetts"],

    ["979", "Texas"],
    ["980", "North Carolina"],
    ["984", "North Carolina"],
    ["985", "Louisiana"],
    ["989", "Michigan"]
];
</script>
<!-- In-memory JS array ends-->

<script type="text/javascript">
YAHOO.example.ACJSArray = new function() {
    // Instantiate first JS Array DataSource
    this.oACDS = new YAHOO.widget.DS_JSArray(YAHOO.example.statesArray);

    // Instantiate first AutoComplete
    this.oAutoComp = new YAHOO.widget.AutoComplete('statesinput','statescontainer', this.oACDS);
    this.oAutoComp.prehighlightClassName = "yui-ac-prehighlight";
    this.oAutoComp.typeAhead = true;
    this.oAutoComp.useShadow = true;
    this.oAutoComp.minQueryLength = 0;
    this.oAutoComp.textboxFocusEvent.subscribe(function(){
        var sInputValue = YAHOO.util.Dom.get('statesinput').value;
        if(sInputValue.length === 0) {
            var oSelf = this;
            setTimeout(function(){oSelf.sendQuery(sInputValue);},0);
        }
    });
    
    // Instantiate second JS Array DataSource
    this.oACDS2 = new YAHOO.widget.DS_JSArray(YAHOO.example.areacodesArray);

    // Instantiate second AutoComplete
    this.oAutoComp2 = new YAHOO.widget.AutoComplete('statesinput2','statescontainer2', this.oACDS2);
    this.oAutoComp2.prehighlightClassName = "yui-ac-prehighlight";
    this.oAutoComp2.typeAhead = true;
    this.oAutoComp2.useShadow = true;
    this.oAutoComp2.forceSelection = true;
    this.oAutoComp2.formatResult = function(oResultItem, sQuery) {
        var sMarkup = oResultItem[0] + " (" + oResultItem[1] + ")";
        return (sMarkup);
    };
};
</script>

</body>
</html>
