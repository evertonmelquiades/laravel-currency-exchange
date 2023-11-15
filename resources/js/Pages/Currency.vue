<script setup>
import axios from "axios";
import { ref, reactive, onMounted } from "vue";
import {router} from '@inertiajs/vue3'

const result = ref(null);

const form = reactive({
    amount: 1,
    from_Currency: 'USD',
    to_Currency: 'EUR',
    result: null,
});

const submitForm = async () => {
    try {
        const response = await axios.post("/currency", form);

        if (response.data.error) {
           await router.get("/limit-reached");
        } else {
            result.value = response.data.result;
        }
    } catch (error) {
        console.error("Error:", error);
    }
};

const swapCurrencies = () => {
    const fromCurrency = form.from_Currency;
    form.from_Currency = form.to_Currency;
    form.to_Currency = fromCurrency;
};

const handleAmountInput = (event) => {
    form.amount = event.target.value.replace(",", ".");
};


onMounted(() => {
    const countries = [
        {
            value: "AUD",
            country: "Australia",
            currency: "Australian Dollar",
        },
        { value: "BGN", country: "Bulgaria", currency: "Bulgarian Lev" },
        { value: "BRL", country: "Brazil", currency: "Brazilian Real" },
        { value: "CAD", country: "Canada", currency: "Canadian Dollar" },
        { value: "CHF", country: "Switzerland", currency: "Swiss Franc" },
        { value: "CNY", country: "China", currency: "Chinese Yuan" },
        { value: "CZK", country: "Czech Republic", currency: "Czech Koruna" },
        { value: "DKK", country: "Denmark", currency: "Danish Krone" },
        { value: "EUR", country: "Eurozone", currency: "Euro" },
        { value: "GBP", country: "United Kingdom", currency: "British Pound" },
        { value: "HKD", country: "Hong Kong", currency: "Hong Kong Dollar" },
        { value: "HRK", country: "Croatia", currency: "Croatian Kuna" },
        { value: "HUF", country: "Hungary", currency: "Hungarian Forint" },
        { value: "IDR", country: "Indonesia", currency: "Indonesian Rupiah" },
        { value: "ILS", country: "Israel", currency: "Israeli New Shekel" },
        { value: "INR", country: "India", currency: "Indian Rupee" },
        { value: "ISK", country: "Iceland", currency: "Icelandic Króna" },
        { value: "JPY", country: "Japan", currency: "Japanese Yen" },
        { value: "KRW", country: "South Korea", currency: "South Korean Won" },
        { value: "MXN", country: "Mexico", currency: "Mexican Peso" },
        { value: "MYR", country: "Malaysia", currency: "Malaysian Ringgit" },
        { value: "NOK", country: "Norway", currency: "Norwegian Krone" },
        {
            value: "NZD",
            country: "New Zealand",
            currency: "New Zealand Dollar",
        },
        { value: "PHP", country: "Philippines", currency: "Philippine Peso" },
        { value: "PLN", country: "Poland", currency: "Polish Złoty" },
        { value: "RON", country: "Romania", currency: "Romanian Leu" },
        { value: "RUB", country: "Russia", currency: "Russian Ruble" },
        { value: "SEK", country: "Sweden", currency: "Swedish Krona" },
        { value: "SGD", country: "Singapore", currency: "Singapore Dollar" },
        { value: "THB", country: "Thailand", currency: "Thai Baht" },
        { value: "TRY", country: "Turkey", currency: "Turkish Lira" },
        {
            value: "USD",
            country: "United States",
            currency: "United States Dollar",
        },
        {
            value: "ZAR",
            country: "South Africa",
            currency: "South African Rand",
        },
    ];
    populateSelectOptions("fromCurrency", countries, "USD");
    populateSelectOptions("toCurrency", countries, "EUR");
});
const populateSelectOptions = (id, countries, defaultValue) => {
    const selectElement = document.getElementById(id);

    countries.forEach((country) => {
        const option = document.createElement("option");
        option.value = country.value;
        option.text = `${country.value} - ${country.country} - ${country.currency}`;
        selectElement.add(option);

        if (country.value === defaultValue) {
            option.selected = true;
        }
    });
}
</script>

<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<template>
    <div id="app" class="container mt-5">
        <div class="card" style="border: 2px solid darkblue">
            <div class="card-header">Currency Exchange</div>
            <div class="container-fluid mt-2">
                <form @submit.prevent="submitForm" class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="amount">Amount</label>
                            <input

                                v-model="form.amount"
                                type="text"
                                name="amount"
                                class="form-control"
                                @input="handleAmountInput"
                            />
                        </div>
                        <div class="col-sm-4">
                            <label for="from_Currency">From:</label>
                            <select
                                v-model="form.from_Currency"
                                id="fromCurrency"
                                name="from_Currency"
                                class="form-control"
                            >

                            </select>
                        </div>
                        <div
                            class="col-sm-1"

                        >
                            <button
                                @click="swapCurrencies"
                                class="bi bi-arrow-left-right btn btn-primary mt-4 m-2"
                                style="float: inline-end;"
                            ></button>
                        </div>
                        <div
                            class="col-sm-4"
                            style="padding-left: 12px; float: left"
                        >
                            <label for="to_Currency">To:</label>
                            <select
                                v-model="form.to_Currency"
                                id="toCurrency"
                                name="to_Currency"
                                class="form-control"
                            >

                            </select>
                        </div>
                    </div>
                    <div class="row row-cols-1">
                        <div
                            class="col"
                            style="padding-bottom: 10px; padding-top: 10px"
                        >
                            <button type="submit" class="btn btn-primary">
                                Exchange
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card text-center mb-2 mt-2">
                <span id="output" style="font-weight: bold">{{ result }}</span>
            </div>
        </div>
    </div>
</template>
