var N = 50;
var width = 800; // グラフの幅
var height = 200; // グラフの高さ
var padding = 30; // スケール表示用マージン

var svg = d3.select("svg");
svg.attr("width", width).attr("height", height*2);


function test_call() {

    // 2. データセットの形成
    var dataset = res_logs.slice(0, N);
    var arr0 = [...Array(N)].map((_, i) => ({"good":0, "bad":0, "datetime": i + "_"}));
    dataset.push(...arr0);
    dataset = dataset.slice(0, N);
    dataset.reverse()
    var max_domain = d3.max(dataset, function(d) { return d.good + d.bad;});

  // 3. 軸スケールの設定
    var xScale = d3.scaleBand()
        .rangeRound([padding, width - padding])
        .padding(0.1)
        .domain(dataset.map(function(d) { return d.datetime; }));

    var pyScale = d3.scaleLinear()
        .domain([0, max_domain])
        .range([height - padding, padding]);

    var myScale = d3.scaleLinear()
        .domain([0, max_domain])
        .range([height - padding, height * 2 - padding * 3]);

  // 3.5 不要な軸やグラフの削除
    svg.selectAll(".svg-axis").remove();
    svg.selectAll(".goodrect").filter(function(d, i) {
        return svg.selectAll(".goodrect").size() >= N - 1 && i == 0;  })
        .remove();
    svg.selectAll(".badrect").filter(function(d, i) {
        return svg.selectAll(".badrect").size() >= N - 1 && i == 0; })
        .remove();
    svg.selectAll(".overrect").filter(function(d, i) {
        return svg.selectAll(".overrect").size() >= N - 1 && i == 0; })
        .remove();
    svg.selectAll("svg g").filter(function(d, i) {
        return svg.selectAll("svg g").size() >= (N - 1) * 3 && i < 3; })
        .remove();

    // 4. 軸の表示
    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform", "translate(" + 0 + "," + (height - padding) + ")")

        .call(d3.axisBottom(xScale).tickFormat(function(d, i) {
            return "";
        }).tickSize([0, 0]));

    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform", "translate(" + 0 + "," + (height * 2 - padding * 2) + ")")
        .call(d3.axisTop(xScale).tickValues(xScale.domain().filter(function(d, i) {
            return (d.slice(-2) == "00" || d.slice(-2) == "30");
        })).tickFormat(function(d, i) {
            if(d.slice(-2) == "00" || d.slice(-2) == "30") return d.slice(-8);
            return "";
        }));

    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform", "translate(" + padding + "," + 0 + ")")
        .call(d3.axisLeft(pyScale).tickFormat(function(d, i) {
        if(d == parseInt(d)) return d;
            return "";
        }).ticks(5));

    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform","translate(" + padding + "," + 0 + ")")
        .call(d3.axisLeft(myScale).tickFormat(function(d, i) {
        if(d == parseInt(d)) return d;
            return "";
        }).ticks(5));
    const moving_mili_sec = 1050;

    // 5. バーの表示
    // 既存上棒
    svg.selectAll(".goodrect")
        .attr("y", function(d) { return pyScale(d.good); })
        .attr("height", function(d) { return height - padding - pyScale(d.good); })
        .transition()
        .duration(moving_mili_sec)
        .ease(d3.easeLinear)
        .attr("x", function(d) {return xScale(d.datetime) - xScale.bandwidth();});

    // 新規上棒
    svg.append("g")
        .selectAll("rect")
        .data(dataset.slice(-1))
        .enter()
        .append("rect")
        .attr("class", "goodrect")
        .attr("x", function(d) { return xScale(d.datetime); })
        .attr("y", function(d) { return height - padding; })
        .attr("width", xScale.bandwidth())
        .attr("fill", "steelblue")
        .transition()
        .duration(moving_mili_sec)
        .ease(d3.easeLinear)
        .attr("y", function(d) { return pyScale(d.good); })
        .attr("height", function(d) { return height - padding - pyScale(d.good); })
        .attr("x", function(d) { return xScale(d.datetime) - xScale.bandwidth();});

    // 既存下棒
    svg.selectAll(".badrect")
        .attr("height", function(d) { return myScale(d.bad) + padding - height; })
        .transition()
        .duration(moving_mili_sec)
        .ease(d3.easeLinear)
        .attr("x", function(d) { return xScale(d.datetime) - xScale.bandwidth();});

    // 新規下棒
    svg.append("g")
        .selectAll("rect")
        .data(dataset.slice(-1))
        .enter()
        .append("rect")
        .attr("class", "badrect")
        .attr("x", function(d) { return xScale(d.datetime); })
        .attr("y", function(d) { return height - padding; })
        .attr("width", xScale.bandwidth())
        .attr("fill", "#C20000")
        .transition()
        .duration(moving_mili_sec)
        .ease(d3.easeLinear)
        .attr("height", function(d) { return myScale(d.bad) + padding - height; })
        .attr("x", function(d) { return xScale(d.datetime) - xScale.bandwidth();});

    // 6. マウスオーバー用透明のバー

    svg.selectAll(".overrect")
        .attr("y", function(d) { return pyScale(d.good); })
        .attr("height", function(d) { return height - padding - pyScale(d.good + d.bad); })
        .transition()
        .duration(moving_mili_sec)
        .ease(d3.easeLinear)
        .attr("x", function(d) { return xScale(d.datetime) - xScale.bandwidth();});


    svg.append("g")
        .selectAll("rect")
        .data(dataset.slice(-1))
        .enter()
        .append("rect")
        .attr("class", "overrect")
        .attr("x", function(d) { return xScale(d.datetime); })
        .attr("y", function(d) { return height - padding; })
        .attr("data-good", function(d) { return d.good; })
        .attr("data-bad", function(d) { return d.bad; })
        .attr("data-time", function(d) { return d.datetime;})
        .attr("width", xScale.bandwidth())
        .attr("fill-opacity", "0")
        .transition()
        .duration(moving_mili_sec)
        .ease(d3.easeLinear)
        .attr("y", function(d) { return pyScale(d.good); })
        .attr("height", function(d) { var x = height - padding - pyScale(d.good + d.bad); return x; })
        .attr("x", function(d) { return xScale(d.datetime) - xScale.bandwidth(); });


}

