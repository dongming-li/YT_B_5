<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper" @click="$event.stopPropagation(); foodItem.modal = false;">
        <div class="modal-container">
            <section class="performance-facts">
                <header class="performance-facts__header">
                    <h1 class="performance-facts__title">Nutrition Facts</h1>
                    <p>Serving Size {{foodItem.servingSize}}</p>
                    <p>Serving Per Container 8</p>
                </header>
                <table class="performance-facts__table">
                    <thead>
                    <tr>
                        <th colspan="3" class="small-info">
                        Amount Per Serving
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th colspan="2">
                        <b>Calories</b>
                        {{foodItem.Calories}}
                        </th>
                        <td>
                        Calories from Fat
                        {{calFromFat}}
                        </td>
                    </tr>
                    <tr class="thick-row">
                        <td colspan="3" class="small-info">
                        <b>% Daily Value*</b>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                        <b>Total Fat</b>
                        {{parseFloat(foodItem["Total Fat"])}}g
                        </th>
                        <td>
                        <b>{{this.calculatePercent(foodItem["Total Fat"], 65)}}%</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="blank-cell">
                        </td>
                        <th>
                        Saturated Fat
                        {{parseFloat(foodItem["Saturated Fat"])}}g
                        </th>
                        <td>
                        <b>{{this.calculatePercent(foodItem["Saturated Fat"], 20)}}%</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="blank-cell">
                        </td>
                        <th>
                        Trans Fat
                        (unavailable)
                        </th>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                        <b>Cholesterol</b>
                        {{parseFloat(foodItem.Cholesterol)}}mg
                        </th>
                        <td>
                        <b>{{this.calculatePercent(foodItem.Cholesterol, 300)}}%</b>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                        <b>Sodium</b>
                        {{parseFloat(foodItem.Sodium)}}mg
                        </th>
                        <td>
                        <b>{{this.calculatePercent(foodItem.Sodium, 2400)}}%</b>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                        <b>Total Carbohydrate</b>
                        {{parseFloat(foodItem["Total Carbohydrate"])}}g
                        </th>
                        <td>
                        <b>{{this.calculatePercent(foodItem["Total Carbohydrate"], 300)}}%</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="blank-cell">
                        </td>
                        <th>
                        Dietary Fiber
                        {{parseFloat(foodItem["Dietary Fiber"])}}g
                        </th>
                        <td>
                        <b>{{this.calculatePercent(foodItem["Dietary Fiber"], 25)}}%</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="blank-cell">
                        </td>
                        <th>
                        Sugars
                        (unavailable)
                        </th>
                        <td>
                        </td>
                    </tr>
                    <tr class="thick-end">
                        <th colspan="2">
                        <b>Protein</b>
                        {{parseFloat(foodItem.Protein)}}g
                        </th>
                        <td>
                        </td>
                    </tr>
                    </tbody>
                </table>
                
                <p class="small-info">* Percent Daily Values are based on a 2,000 calorie diet. Your daily values may be higher or lower depending on your calorie needs:</p>
                
                <table class="performance-facts__table--small small-info">
                    <thead>
                    <tr>
                        <td colspan="2"></td>
                        <th>Calories:</th>
                        <th>2,000</th>
                        <th>2,500</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th colspan="2">Total Fat</th>
                        <td>Less than</td>
                        <td>65g</td>
                        <td>80g</td>
                    </tr>
                    <tr>
                        <td class="blank-cell"></td>
                        <th>Saturated Fat</th>
                        <td>Less than</td>
                        <td>20g</td>
                        <td>25g</td>
                    </tr>
                    <tr>
                        <th colspan="2">Cholesterol</th>
                        <td>Less than</td>
                        <td>300mg</td>
                        <td>300 mg</td>
                    </tr>
                    <tr>
                        <th colspan="2">Sodium</th>
                        <td>Less than</td>
                        <td>2,400mg</td>
                        <td>2,400mg</td>
                    </tr>
                    <tr>
                        <th colspan="3">Total Carbohydrate</th>
                        <td>300g</td>
                        <td>375g</td>
                    </tr>
                    <tr>
                        <td class="blank-cell"></td>
                        <th colspan="2">Dietary Fiber</th>
                        <td>25g</td>
                        <td>30g</td>
                    </tr>
                    </tbody>
                </table>
                
                <p class="small-info">
                    Calories per gram:
                </p>
                <p class="small-info text-center">
                    Fat 9
                    &bull;
                    Carbohydrate 4
                    &bull;
                    Protein 4
                </p>
                
            </section>
          
            
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
    export default {
        data(){
            return{}
        },
        props:{
            foodItem:{
                default:()=>{}
            }
        },
        computed:{
            calFromFat(){
                //9 calories per gram of fat
                return parseFloat(this.foodItem["Total Fat"]) * 9;
            }
        },
        methods:{
            calculatePercent(foodVal, total){
                return Math.round(parseFloat(foodVal)/total * 100);
            }
        }
    }
</script>

<style>
    .modal-mask {
        color:#666666;
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        overflow:overlay;
        width: 80%;
        max-width:350px;
        min-width:225px;
        margin: 0px auto;
        padding: 10px 13px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }

    body {
        font-size: small;
        line-height: 1.4;
    }

    p {
    margin: 0;
    }

    .performance-facts {
    border: 1px solid black;
    margin: 20px;
    float: left;
    width: 100%;
    padding: 0.5rem;
    margin: auto;
    display: block;
    }
    .performance-facts table {
    border-collapse: collapse;

    }

    .performance-facts__title {
    font-weight: bold;
    font-size: 2rem;
    margin: 0 0 0.25rem 0;
    }

    .performance-facts__header {
    border-bottom: 10px solid black;
    padding: 0 0 0.25rem 0;
    margin: 0 0 0.5rem 0;
    }
    .performance-facts__header p {
    margin: 0;
    }

    .performance-facts__table{
        width: 100%;
    }
    .performance-facts__table--small, .performance-facts__table--grid {
    width: 100%;
    }
    .performance-facts__table thead tr th, .performance-facts__table--small thead tr th, .performance-facts__table--grid thead tr th, .performance-facts__table thead tr td, .performance-facts__table--small thead tr td, .performance-facts__table--grid thead tr td {
    border: 0;
    }
    .performance-facts__table th, .performance-facts__table--small th, .performance-facts__table--grid th, .performance-facts__table td, .performance-facts__table--small td, .performance-facts__table--grid td {
    font-weight: normal;
    text-align: left;
    padding: 0.25rem 0;
    border-top: 1px solid black;
    width: 56%;
    
    }
    .performance-facts__table td:last-child, .performance-facts__table--small td:last-child, .performance-facts__table--grid td:last-child {
    text-align: right;
    }
    .performance-facts__table .blank-cell, .performance-facts__table--small .blank-cell, .performance-facts__table--grid .blank-cell {
    width: 1rem;
    border-top: 0;
    }
    .performance-facts__table .thick-row th, .performance-facts__table--small .thick-row th, .performance-facts__table--grid .thick-row th, .performance-facts__table .thick-row td, .performance-facts__table--small .thick-row td, .performance-facts__table--grid .thick-row td {
    border-top-width: 5px;
    }

    .small-info {
    font-size: 0.7rem;
    }

    .performance-facts__table--small {
    border-bottom: 1px solid #999;
    margin: 0 0 0.5rem 0;
    }
    .performance-facts__table--small thead tr {
    border-bottom: 1px solid black;
    }
    .performance-facts__table--small td:last-child {
    text-align: left;
    }
    .performance-facts__table--small th, .performance-facts__table--small td {
    border: 0;
    padding: 0;
    }

    .performance-facts__table--grid {
    margin: 0 0 0.5rem 0;
    }
    .performance-facts__table--grid td:last-child {
    text-align: left;
    }
    .performance-facts__table--grid td:last-child::before {
    content: "•";
    font-weight: bold;
    margin: 0 0.25rem 0 0;
    }

    .text-center {
    text-align: center;
    }

    .thick-end {
    border-bottom: 10px solid black;
    }

    .thin-end {
    border-bottom: 1px solid black;
    }

</style>
