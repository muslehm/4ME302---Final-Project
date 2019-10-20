function init(myyaxis, myaxis, mytime, mybutton, mycanvas, type, fullnote) {
	// set these values to pass a long, 
    //values pass several times for better maintenance of the code if it crashes
    var yAxis =  myyaxis;
    var timeS = mytime;
	var xAxis = myaxis; 
	var canvas = mycanvas;
    var buttoncolor = mybutton;
    var notesArray = fullnote; 
    
    
   
  if(type==1)
	plotSpiral(yAxis, xAxis, canvas, timeS, notesArray);
   else
    plotButtons(yAxis, xAxis, canvas, timeS, buttoncolor, notesArray);
    }

function plotButtons(dataSety, dataSetx, myelement, duration, thecolor, thenote) {
    //two arrays one for button graph another for Line graph, they are formatted differently
    var arrb = [];
    var arrL = [];
    var arrLC = [];
    //new array of time difference between clicks
    var diff = [];
    arrblength = dataSetx.length;
 
    //create time difference array
    for(let i=0;i<arrblength;i++)
    {
        if(i!=0)
        {   if(duration[i]!=0)
           { diff.push((duration[i]-duration[i-1])/1000);}
            else if(i>0 && duration[i]==0)
               {break;}
            else
             {diff.push(0);}}
        else
           { diff.push(0);}
    }

    //add datasets into an array
    
    for(let i=0;i<diff.length;i++)
	{
		arrb.push([dataSetx[i], dataSety[i], thecolor[i]]);
        arrL.push({"x":duration[i]/1000, "y":diff[i]});
        arrLC.push([duration[i]/1000, diff[i], thecolor[i]]);
        
	}
    

    var maxD = Math.max.apply(null,duration);
    var maxDiff = Math.max.apply(null,diff);
    ////////////////////
    ///SVG of Line Chart
    /////////////////////
    // dimensions and margins of the graph (using it for best practice)
    var margin = {top: 20, right: 20, bottom: 40, left: 60},
    width = 500 - margin.left - margin.right,
    height = 300 - margin.top - margin.bottom;

    // create svg element, putting the width by adding what we subtracted above
    var svgL = d3.select("#"+myelement+"L").append("svg")
    .attr("width", width + margin.left + margin.right).attr("height", height + margin.top + margin.bottom)
    .append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    // Creating and adding X axis
    var xaxisScale = d3.scaleLinear().domain([0, maxD/1000]).range([0, width]);
    svgL.append("g").attr("transform", "translate(0," + height + ")").call(d3.axisBottom(xaxisScale));

    // Creating and adding Y axis
    var yaxisScale = d3.scaleLinear().domain([0, maxDiff]).range([ height, 0]);
    svgL.append("g").call(d3.axisLeft(yaxisScale));

    // Assigning X axis label
    svgL.append("text")
    .attr("text-anchor", "end").attr("x", 200).attr("y", height + margin.top + 20)
    .text("Duration in seconds");

    // Assigning Y axis label
    svgL.append("text")
    .attr("text-anchor", "end").attr("transform", "rotate(-90)").attr("y", -margin.left+20).attr("x", -height/2)
    .text("Delay in seconds");
   
    var line = d3.line().x(function(d, i) { return xaxisScale(d.x); })
    .y(function(d) {return yaxisScale(d.y); }).curve(d3.curveLinear);
    // Create Line
    svgL.append("path").datum(arrL).attr("class", "line").attr("d", line); 
    //add dots
    svgL.selectAll("circle").data(arrLC).enter().append("circle").attr("cx", function(d, i){ return xaxisScale(d[0]) })
    .attr("cy", function(d) { return yaxisScale(d[1]) }).attr("r", 2.5).attr("fill",function(d) {return d[2];});


    //creating annotations and adding them to the graph
    var arrayofX;
    var arrayofY;
    var arrayofN;
    var arrayNotes =[];
    var strposy;
    var strposz;
    for(let i=0;i<thenote.length;i++)
    {
        strposy = thenote[i]['note'].indexOf('y');
        strposz = thenote[i]['note'].indexOf('z');

        arrayofX=parseInt(thenote[i]['note'].substr(1, strposy-1))-60;
        arrayofY=parseInt(thenote[i]['note'].substr(strposy+1, strposz-strposy-1))-20;
        arrayofN=thenote[i]['note'].substr(strposz+1);
        arrayNotes.push({"x":arrayofX, "y":arrayofY, "n":arrayofN});
    }
     for(let i=0;i<arrayNotes.length;i++)
    {

    var annotations = [
            {note: {label: arrayNotes[i].n}, x: arrayNotes[i].x, y: arrayNotes[i].y}];
            
    var makeAnnotations = d3.annotation().editMode(true).annotations(annotations);
        svgL.append("g").call(makeAnnotations);
    }
    

    /////////////////////
    //SVG of the Buttons
    /////////////////////
    var svg = d3.select("#"+myelement).append("svg").attr("width", 300).attr("height", 100);    
    //Illustrate clicks with dots
    svg.selectAll("circle").data(arrb).enter().append("circle").attr("cx", function(d) {return d[0];})
    .attr("cy", function(d) {return d[1];}).attr("r", 2.5).attr("fill",function(d) {return d[2];});
    //Add left rectangle to rsemble left button 
    var rectangle = svg.append("rect").attr("x", 25).attr("y", 35).attr("width", 45).attr("height", 36)
    .style("stroke", "gray").style("fill", "none");
    //Add right rectangle to rsemble right button 
    var rectangle2 = svg.append("rect").attr("x", 170).attr("y", 35).attr("width", 45).attr("height", 36)
    .style("stroke", "gray").style("fill", "none");
    

    
    }
function plotSpiral(dataSety, dataSetx, myelement, duration, thenote) {

    var width = 300;
    var height = 300;
    var arr = [];
    var arrSL= [];
    var pixeldiff = [];
    var radiusD = [];
    arrlength = dataSetx.length;
    
   for(let i=0;i<duration.length;i++)
   {
       if(i!=0)
        radiusD.push(Math.sqrt(Math.pow((dataSetx[i]-dataSetx[0]),2)+Math.pow((dataSety[i]-dataSety[0]),2)));
       else
       radiusD.push(0);

   }
    for(let i=0;i<duration.length;i++)
   {

        if(i!=0)
            pixeldiff.push(radiusD[i]-radiusD[i-1]);
        else
            pixeldiff.push(0);
   }

    //add datasets into an array
    for(let i=0;i<arrlength;i++)
	{
        arr.push({"x":dataSetx[i], "y":dataSety[i]});
        arrSL.push({"x":duration[i]/1000, "y":pixeldiff[i]});
	}

    var maxD = Math.max.apply(null,duration);
    var maxDiff = Math.max.apply(null,pixeldiff);
    var minDiff = Math.min.apply(null,pixeldiff);

    
     //////////////////////
    ////Draw Line Graph////
    //////////////////////

    // dimensions and margins of the graph (using it for best practice)
    var margin = {top: 20, right: 20, bottom: 40, left: 60},
    width = 550 - margin.left - margin.right,
    height = 300 - margin.top - margin.bottom;

    // create svg element, putting the width by adding what we subtracted above
    var svgL = d3.select("#"+myelement+"L").append("svg")
    .attr("width", width + margin.left + margin.right).attr("height", height + margin.top + margin.bottom)
    .append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    //.on('click', showCoords.bind(this))

    // Creating and adding X axis
    var xaxisScale = d3.scaleLinear().domain([0, maxD/1000]).range([0, width]);
    svgL.append("g").attr("transform", "translate(0," + height/1.5 + ")").call(d3.axisBottom(xaxisScale)).select(".domain").remove();
    

    // Creating and adding Y axis
    var yaxisScale = d3.scaleLinear().domain([minDiff, maxDiff]).range([ height/1.5, 0]);
    svgL.append("g").attr("transform", "translate(-10,0)").call(d3.axisLeft(yaxisScale));

    // Assigning X axis label
    svgL.append("text")
    .attr("text-anchor", "end").attr("x", 200).attr("y", height/1.5 + margin.top + 20)
    .text("Duration in seconds");

    // Assigning Y axis label
    svgL.append("text")
    .attr("text-anchor", "end").attr("transform", "rotate(-90)").attr("y", -margin.left+20).attr("x", -height/2)
    .text("Pixels");
   
    var line = d3.line().x(function(d, i) { return xaxisScale(d.x); }).y(function(d) {return yaxisScale(d.y); }).curve(d3.curveLinear);
    // Create Line
    svgL.append("path").datum(arrSL).attr("class", "lineSpiral").attr("d", line); 
    var arrayofX;
    var arrayofY;
    var arrayofN;
    var arrayNotes =[];
    var strposy;
    var strposz;
    for(let i=0;i<thenote.length;i++)
    {
        strposy = thenote[i]['note'].indexOf('y');
        strposz = thenote[i]['note'].indexOf('z');

        arrayofX=parseInt(thenote[i]['note'].substr(1, strposy-1))-60;
        arrayofY=parseInt(thenote[i]['note'].substr(strposy+1, strposz-strposy-1))-20;
        arrayofN=thenote[i]['note'].substr(strposz+1);
        arrayNotes.push({"x":arrayofX, "y":arrayofY, "n":arrayofN});
    }
    
    //Create the annotations
   
   for(let i=0;i<arrayNotes.length;i++)
    {

    var annotations = [
            {note: {label: arrayNotes[i].n}, x: arrayNotes[i].x, y: arrayNotes[i].y}];
            
    var makeAnnotations = d3.annotation().editMode(true).annotations(annotations);
       svgL.append("g").call(makeAnnotations);
     
    }
    

    ///////////////////
    ////Draw Spiral////
    //////////////////

    //the line function
    var lineFunction = d3.line().x(function(d) { return d.x; }).y(function(d) { return d.y; });
    

    //add an SVG element
    var svgContainer = d3.select("#"+myelement).append("svg").attr("width", width).attr("height", height);
                                    
    //add path to the SVG
    var lineGraph = svgContainer.append("path").attr("d", lineFunction(arr)).attr("stroke", "blue").attr("stroke-width", 2).attr("fill", "none");

    }

function graphbar(newarray){   
    
    
    var dates =newarray[0];
    var tests =newarray[1]; 
    var maxvalue = Math.max.apply(null,tests);

    var svgWidth = 210, svgHeight= maxvalue*100+50, barPadding = 40;
    var barWidth = (svgWidth/tests.length);
    var svgB =d3.select(".patchart").append("svg")
    .attr("width", svgWidth).attr("height", svgHeight);
    var datarray = dates;
    datarray[0] = dates[0].slice(0, 10);
    datarray[1] = dates[1].slice(0, 10);
        
    var barChart = svgB.selectAll("rect").data(tests).enter()
    .append("rect")
    .attr("y", function(d){return svgHeight - maxvalue*100})
    .attr("height", function(d){return d*50}).attr("width", barWidth - barPadding)
    .attr("transform", function(d, i)
    {var translate = [barWidth*i+40, 0];
    return "translate("+ translate +")";});

    var text = svgB.selectAll("text").data(datarray).enter().append("text")
        .text(function(d){ return d ;})
        .attr("y", function(d, i){return svgHeight - maxvalue*100-5;})
        .attr("x", function(d, i){return barWidth*i+30;})
        .attr("fill","#A64C38");
    
    // Creating and adding Y axis
    var yaxisScale = d3.scaleLinear().domain([maxvalue, 0]).range([ maxvalue*50+60, 60]);
    svgB.append("g").attr("transform", "translate(30,-10)").call(d3.axisLeft(yaxisScale).tickValues([maxvalue, 1, 0]));
    
    
    
  

    }
function showCoords(event, notename) {
   
  
  var x = event.offsetX;
  var myn = notename.id+"n";
  var y = event.offsetY;
  var coords = "x" + x + "y" + y;
  document.getElementById(myn).value = coords;
    }


