<template>
    <div>
    <br>
        <h4> Hello, {{userSettings.netId}}! </h4>
        <h5>{{currentDate}} </h5>
         <h6><a> <router-link to="/profile">  Edit User Profile  </router-link></a> </h6>
        <hr>
         
        
        <h5> {{totalMacros.calories}} / {{userSettings.goals.calories}} Calories </h5>
        <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" 
            v-bind:style="{width:totalMacros.calories/userSettings.goals.calories * 100 + 5 + '%'}"></div>
        </div>
    
        <div class="row">
            <div class="col-xs-2">Fat:</div>
            <div class="col-xs-7">  
                 <div class="progress" style="margin-top:.5em">
                     <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" 
                      v-bind:style="{width:totalMacros.fat/userSettings.goals.fat * 100 + 5 + '%'}"></div>
                </div>
            </div>
            <div class="col-xs-3">{{totalMacros.fat}}/{{userSettings.goals.fat}}</div>
        </div>

        <div class="row">
            <div class="col-xs-2">Carbs</div>
            <div class="col-xs-7">  
                 <div class="progress" style="margin-top:.5em">
                     <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" 
                      v-bind:style="{width:totalMacros.carbs/userSettings.goals.carbs * 100 + 5 + '%'}"></div>
                </div>
            </div>
             <div class="col-xs-3">{{totalMacros.carbs}}/{{userSettings.goals.carbs}} </div>
        </div>

        <div class="row">
            <div class="col-xs-2" >Protein</div>
            <div class="col-xs-7">  
                 <div class="progress" style=" margin-top:.5em;">
                     <div class="progress-bar progress-bar-infor" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" 
                      v-bind:style="{width:totalMacros.protein/userSettings.goals.protein * 100 + 5 + '%'}"></div>
                </div>
            </div>
              <div class="col-xs-3">{{totalMacros.protein}}/{{userSettings.goals.protein}} </div>
        </div>  
    </div>
</template>

<script>
    export default {

        computed:{
           foodLog: function(){
               return this.$store.state.foodLog
           },
           userSettings: function(){
                return this.$store.state.userSettings
           },
           
            totalMacros: function() {
                let macros = {
                    calories:0,
                    fat:0,
                    carbs: 0,
                    protein: 0
                }

                for (let id in this.foodLog){
                    let item = this.foodLog[id]
                    macros.calories +=  (this.formatMacros(item.food['Calories']) * item.servings)
                    macros.fat += (this.formatMacros(item.food['Total Fat']) * item.servings)
                    macros.carbs += (this.formatMacros(item.food['Total Carbohydrate']) * item.servings)
                    macros.protein += (this.formatMacros(item.food['Protein']) * item.servings)
                }
                return macros
            },
             currentDate() {
                return new Date().toDateString()
            }
        
            
        },
        methods : {
             formatMacros:function(str){
                return isNaN(parseInt(str)) ? 0:parseInt(str);
            },
        },
      
        mounted() {
    
        }
    }
</script>

<style>
      button a {
        color:white;
    }
</style>
