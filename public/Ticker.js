function Ticker(ws_url, calculator) {
    var me = this;

    me._stop = false;
    me.calculator = calculator;
    me.conn = new WebSocket(ws_url);

    me.conn.onopen = function(e) {
        console.log("Connection established!");
    };

    me.conn.onmessage = Ticker.prototype.onmessage.bind(me);

    me.interval = setInterval(
        function() {
            var res = calculator.calculate();
            me.markTable(res['exchanges']);

            document.getElementById('btc_usd').innerHTML = res['btc_usd']['mean'] || '---';
            document.getElementById('euro_usd').innerHTML = res['euro_usd']['mean'] || '---';
            document.getElementById('btc_euro').innerHTML = res['btc_euro']['mean'] || '---';
        },
        3000
    );
}

Ticker.prototype.onmessage = function onmessage(e) {
    if (this._stop) {
        return;
    }
    var exchangeRate = JSON.parse(e.data);
    if (this.calculator.addExchangeRate(exchangeRate)) {
        this.addExchangeToDom(exchangeRate);
    }
};

Ticker.prototype.addExchangeToDom = function(exchangeRate) {
    var existingRow = document.getElementById(exchangeRate.provider_name);
    if (existingRow) {
        existingRow.remove()
    }

    var table = document.getElementById("providers");
    var row = document.createElement('tr');

    row.setAttribute('id', exchangeRate.provider_name);

    function createTd(text) {
        var td = document.createElement('td');
        td.innerText = text;
        return td;
    }

    var d = new Date(0); // The 0 there is the key, which sets the date to the epoch
    d.setUTCSeconds(exchangeRate.time);

    row.appendChild(createTd(exchangeRate.provider_name));
    row.appendChild(createTd(exchangeRate.type));
    row.appendChild(createTd(exchangeRate.value));
    row.appendChild(createTd(d.toString('H:mm:ss')));
    // row.appendChild(createTd(d.toISOString().substr(11, 8)));
    row.appendChild(createTd(exchangeRate.expiration_interval));

    table.insertBefore(row, table.firstChild);
}

Ticker.prototype.markTable = function(exchanges) {
    exchanges.forEach(function(exchange) {
       if ('is_active' in exchange) {
           var row = document.getElementById(exchange.provider_name);
           if (row) {
               if (exchange.is_active) {
                   // mark as green
                   row.style.background = 'green';
               } else {
                   //mark as red
                   row.style.background = 'red';
               }
           }
       }
    });
}

Ticker.prototype.stop = function() {
    this._stop = true;
    clearInterval(this.interval);
}