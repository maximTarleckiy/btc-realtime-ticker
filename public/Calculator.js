function Calculator() {
    this.exchanges = [];
}

Calculator.prototype.addExchangeRate = function(exchangeRate) {

    var add = true;
    this.exchanges.filter(function(element) {
            if (element.provider_name === exchangeRate.provider_name) {
                // previous exchange can be more recent
                if (element.time < exchangeRate.time) {
                    return false;
                } else {
                    add = false;
                }
            }
            return true;
        }
    );
    if (add) {
        this.exchanges.push(exchangeRate);
        return true;
    }
    return false;
}

Calculator.prototype.calculate = function () {

    var btc_usd = [];
    var euro_usd = [];

    var current_time = Math.floor(Date.now() / 1000);
    this.exchanges.forEach(function(exchange) {
            if  (!exchange.value || (current_time - exchange.time) > exchange.expiration_interval) {
                // not calculate expired rates
                exchange.is_active = false;
                return;
            }
            exchange.is_active = true;

            if (exchange.type === 'euro_usd') {
                euro_usd.push(exchange.value);
            }
            if (exchange.type === 'btc_usd') {
                btc_usd.push(exchange.value);
            }
        }
    );

    var btc_usd_mean = this.calculateMean(btc_usd);
    var euro_usd_mean = this.calculateMean(euro_usd);
    var bitcoin_euro_mean = 0;
    if (euro_usd_mean) {
        bitcoin_euro_mean = btc_usd_mean / euro_usd_mean;
    }

    return {
        'exchanges': this.exchanges,
        'btc_usd': {
            'mean': Number(btc_usd_mean.toFixed(2)),
            'active_sources': btc_usd.length
        },
        'euro_usd': {
            'mean': Number(euro_usd_mean.toFixed(2)),
            'active_sources': euro_usd.length
        },
        'btc_euro': {
            'mean': Number(bitcoin_euro_mean.toFixed(2))
        }
    }
};

Calculator.prototype.calculateMean = function(ar) {
    function add(a, b) {
        return a + b;
    }
    if (typeof ar === 'object' && ar.length) {
        var sum = ar.reduce(add, 0);
        if (sum) {
            return sum / ar.length;
        }
    }
    return 0;
}