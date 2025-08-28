//Restricted Number Inputs
function restrictInputToNumbersWithRange(input, min, max) {
    // Удаление всех символов, кроме цифр во время ввода
    input.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // Проверка диапазона значений при потере фокуса (после окончания ввода)
    input.addEventListener('blur', function() {
        let value = parseInt(this.value, 10);

        // Если значение пустое или не число, оставить поле пустым
        if (isNaN(value)) {
            this.value = '';
        } else if (value < min) {
            this.value = min;
        } else if (value > max) {
            this.value = max;
        }
    });

    // Запрещаем вставку значений, которые не являются числами или выходят за диапазон
    input.addEventListener('paste', function(e) {
        let pastedData = e.clipboardData.getData('text');
        let value = parseInt(pastedData, 10);
        if (isNaN(value) || value < min || value > max) {
            e.preventDefault();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const inputsConfig = [
        { id: 'input-price-apart-hour', min: 100, max: 1000000 },
        { id: 'input-price-out-hour', min: 100, max: 1000000 },
        { id: 'input-price-apart-hour', min: 100, max: 1000000 },
        { id: 'input-price-out-hour', min: 100, max: 1000000 },
        { id: 'input-price-apart-hour', min: 100, max: 1000000 },
        { id: 'input-price-out-hour', min: 100, max: 1000000 },
        { id: 'input-express-price', min: 100, max: 1000000 },
    ];
    inputsConfig.forEach(function(config) {
        const inputElement = document.getElementById(config.id);
        if (inputElement) {
            restrictInputToNumbersWithRange(inputElement, config.min, config.max);
        }
    });
});
//Restricted Number Inputs

//Display Blocks
const citySelect = document.getElementById("select-city");
const zoneSelect = document.getElementById("input-zone");
const zoneParent = zoneSelect.parentElement.parentElement;
const metroSelect = document.getElementById("input-metro");
const metroParent = metroSelect.parentElement.parentElement;

function checkLocationSelect() {
    zoneParent.style.display = zoneSelect.options.length === 0 ? "none" : "block";
    metroParent.style.display = metroSelect.options.length === 0 ? "none" : "block";
}

checkLocationSelect();
setInterval(checkLocationSelect, 500);

citySelect.addEventListener("change", getCityData);

function getCityData() {
    var city = $('#select-city').val();
    $.ajax({
        url: '/services/get_city_data',
        type: 'get',
        data: {'_token': document.querySelector('input[name="_token"]').value, 'city_id': city},
        dataType: 'json',
        success: function (jsonData) {
            //Region
            zoneSelect.innerHTML = '';
            for (let i = 0; i < jsonData.zone.length; i++) {
                let option = document.createElement('option');
                option.value = jsonData.zone[i].id;
                option.text = jsonData.zone[i].title;
                zoneSelect.add(option);
            }
            //Metro
            metroSelect.innerHTML = '';
            for (let i = 0; i < jsonData.metro.length; i++) {
                let option = document.createElement('option');
                option.value = jsonData.metro[i].id;
                option.text = jsonData.metro[i].title;
                metroSelect.add(option);
            }
        }
    });
}

//getCityData();
//Display Blocks
