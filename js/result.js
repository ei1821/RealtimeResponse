var N = 50; // 一度に表示するバーの数
var width = 800; // 横幅
var height = 200;
var padding = 30;
const MAGNIFICATION = 4; // 一度の拡大による倍率の変化度

var svg = d3.select("svg");
svg.attr("width", width).attr("height", height*2);

var first = dataset[0], end = dataset.slice(-1)[0];
first = new Date(first.datetime);
end   = new Date(end.datetime);
var data_len = idv(end.getTime() - first.getTime(), 1000);
var max_domain = d3.max(dataset, function(d) { return d.good + d.bad;});


/* setup 引数: なし
    */
function setup() {
    var ds = Array();
    var n = N;
    if(data_len < n) n = data_len;
    var step = ( 0 | data_len / n);

    for(let i = 1; i <= n; ++i) { // 最後の要素がルームを閉じた時間になるようにする
        let target = i * step + data_len % n; // i本目のバーのdatetime
        let target_utime = first.getTime() + target * 1000;
        var idx = binary_search(dataset, target_utime, function(a, b) { return new Date(a.datetime).getTime() < b; });
        if(idx > 0) idx--;
        var tmp = deep_copy(dataset[idx]);
        tmp.datetime = getDateTime(target_utime);
        ds.push(tmp);
    }
    return ds;
}
/*
	DATETIMEが[left, right)内のコメントのリストを返す
*/
function comments_range(left, right) {
	var l_idx = binary_search(comments, left, (a, b) => new Date(a.datetime).getTime() <  b);
	var r_idx = binary_search(comments, right,(a, b) => new Date(a.datetime).getTime() <= b);
	return deep_copy(comments.slice(l_idx, r_idx));
}


/*
	引数: 	datetime - 基準となるバーのdatetime形式の時間
			rate - 拡大倍率の状態 全体に対してrate ** MAGNIFACATIONだけズームされる
	返り値:	新たなdateset
*/
function zoom_graph(datetime, rate = 0) {
	if(rate < 0) return zoom_graph(datetime);
	var n = N;
	if(data_len < n) n = data_len;
	var new_data_len = idv(data_len, MAGNIFICATION ** rate);
	if(new_data_len < n) {
		if(rate != 0)
			return zoom_graph(datetime, rate-1);
		new_data_len = N;
	}
	var ds = Array();
	var step = idv(new_data_len, n);

	var base_utime = new Date(datetime).getTime();
	var new_first = base_utime - idv(new_data_len, 2) * 1000,
		new_last  = base_utime + idv(new_data_len, 2) * 1000;

	for(let i = 1; i <= n; ++i) {
        let target = i * step + new_data_len % n; // i本目のバーのdatetime
        let target_utime = new_first + target * 1000;
        var idx = binary_search(dataset, target_utime, function(a, b) { return new Date(a.datetime).getTime() < b; });
        if(idx > 0) idx--;
        var tmp = deep_copy(dataset[idx]);
        tmp.datetime = getDateTime(target_utime);
        ds.push(tmp);
	}
	return ds;
}

/* 引数: 表示するバー情報のリスト (length <= N) */
function make_graph(dataset) {
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
    svg.selectAll(".goodrect").remove();
    svg.selectAll(".badrect").remove();
    svg.selectAll(".overrect").remove();
    svg.selectAll("svg g").remove();

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
            return !((i + 1)%10) || !i;
        })).tickFormat(function(d, i) {
            return d.slice(-8);
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

    // 上バーの表示
    svg.append("g")
        .selectAll("rect")
        .data(dataset)
        .enter()
        .append("rect")
        .attr("class", "goodrect")
        .attr("x", d => xScale(d.datetime))
        .attr("y", d => pyScale(d.good))
        .attr("width", xScale.bandwidth())
        .attr("height", d => height - padding - pyScale(d.good))
        .attr("fill", "steelblue");

    // 下バー表示
    svg.append("g")
        .selectAll("rect")
        .data(dataset)
        .enter()
        .append("rect")
        .attr("class", "badrect")
        .attr("x", d => xScale(d.datetime))
        .attr("y", d => height - padding)
        .attr("width", xScale.bandwidth())
        .attr("height", d => myScale(d.bad) + padding - height)
        .attr("fill", "#C20000");

    // マウスオーバー用透明のバー
    svg.append("g")
        .selectAll("rect")
        .data(dataset)
        .enter()
        .append("rect")
        .attr("class", "overrect")
        .attr("x", d => xScale(d.datetime))
        .attr("y", d => pyScale(d.good))
        .attr("data-good", d => d.good)
        .attr("data-bad",  d => d.bad)
        .attr("data-time", d => d.datetime)
        .attr("width", xScale.bandwidth())
        .attr("height", d => height - padding - pyScale(d.good + d.bad))
        .attr("fill-opacity", 0);

}


const binary_search = (arr, val, func, first = 0, last = arr.length) => {
    first -= 1;
    for (let m; last - first > 1;) {
        if (func(arr[m = first + (0 | (last - first) / 2)] , val))
            first = m;
        else
            last = m;
    }
    return last;
}

const upper_bound = (arr, val, first = 0, last = arr.length) => {
    first -= 1;
    while (last - first > 1) {
        const mid = first - Math.floor((last - first) / 2);
        if (arr[mid] <= val) // ココを直すだけ
            first = mid;
        else
            last = mid;
    }
    return last;
}
