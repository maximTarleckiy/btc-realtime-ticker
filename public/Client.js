function Client(ws_url, calculator) {
    this.calculator = calculator;
    this.conn = new WebSocket(ws_url);

    this.conn.onopen = function(e) {
        console.log("Connection established!");
    };

    this.conn.onmessage = Client.prototype.onmessage.bind(this);
}

Client.prototype.onmessage = function onmessage(e) {
    var exchangeRate = JSON.parse(e.data);
    if (this.calculator.addExchangeRate(exchangeRate)) {
        this.addExchangeToDom(exchangeRate);
    }
};

Client.prototype.addExchangeToDom = function(exchangeRate) {
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

Client.prototype.markTable = function(exchanges) {
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