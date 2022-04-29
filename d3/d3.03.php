<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>D3 Test</title>
    <link rel="stylesheet" href="./test.css" type="text/css"/>
  <script src="https://d3js.org/d3.v7.min.js"></script>
  </head>
  <body>
  <div class="box">
  <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
</div>
  <div id="svgdiv">
    <svg id="svg" width="500" height="100" viewbox="0 0 400 400">
    </svg>

  </div>
    <script>
      var svg = d3.select("#svg");
      var dataset = [1, 2, 3, 4, 5];
      var circles = svg.selectAll("circle")
                       .data(dataset)
                       .enter()
                       .append("circle")
      .attr("cx", function(d, i) {
        return (i * 50) + 25;
      })
      .attr("cy", 50)
      .attr("r", function(d) {
        return 5 * d;
      })
      .attr("fill", "yellow")
      .attr("stroke", "orange")
      .attr("stroke-width", function(d) {
        return d;
      })
      .attr("id", function(d) {
        return "circ" + d ;
      })
      ;

     circles
        .on("click", function(data,idx, elem) {
            var circ = d3.select("#circ" + idx);
            var r = d3.randomUniform(20)() + 5;
            circ.attr("r", r);
            console.log(r);
        });

      function resize(obj) {
        obj.attr("r", r);
        console.log(obj.attr("r"));
      }

      function addition() {
        var circls = svg.selectAll("circle")
                       .attr("r", d3.randomUniform(20)() + 5);
        dataset.push(dataset.length+1);
        var newCircle = svg
                       .data(dataset)
                       .enter()
                       .append("circle")
                       .attr("cx", function(d, i) {
                        return (i * 50) + 25;
                       })
                       .attr("cy", 50)
                      .attr("r", function(d) {
                         return 5 * d;
                      })
                      .attr("fill", "yellow")
                      .attr("stroke", "orange")
                      .attr("stroke-width", function(d) {
                        return d;
                      })
                      .attr("id", function(d,i) {
                        return "circ" + i ;
                      })
                       ;

    console.log(dataset);

      }
    </script>

    <br>
    <div>
      <a onclick="addition();">test</ai>
    </div>
  </body>
</html>

