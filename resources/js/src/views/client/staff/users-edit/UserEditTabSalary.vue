<template>
  <div>

    <!-- Header: Personal Info -->
    <div class="d-flex">
      <feather-icon
        icon="DollarSignIcon"
        size="19"
      />
      <h4 class="mb-0 ml-50">
        Salary & Rate
      </h4>
    </div>

    <!-- Form: Personal Info Form -->
    <b-form class="mt-1">
      <b-row>

        <b-container fluid>
          <b-row>

                   <!-- Weekly working time -->
        <b-col>
          <b-form-group
            label="Weekly working time (Hours per week)"
            label-for="weeklyworkingtime"
          >
            <cleave
              id="weeklyworking"
              v-model="userDataInfo.weeklyworkingtime"
              class="form-control"
              :raw="false"
              :options="options.time"
              placeholder="hh:mm"
            />
          </b-form-group>
        </b-col>
  
        <!-- Hourly Salary -->
        <b-col>
          <b-form-group
            label="Hourly Salary (price in €uro)"
            label-for="hourly_salary"
          >
            <cleave
              id="hourly"
              v-model="userDataInfo.hourly_salary"
              class="form-control"
              :raw="false"
              :options="options.hourly"
              placeholder="10€"
            />
          </b-form-group>
        </b-col>
  
        <!-- Monthly Salary -->
        <b-col>
          <b-form-group
            label="Monthly Salary (price in €uro)"
            label-for="monthly_salary"
          >
            <cleave
              id="monthly"
              v-model="userDataInfo.monthly_salary"
              class="form-control"
              :raw="false"
              :options="options.monthly"
              placeholder="3,000€"
            />
          </b-form-group>
        </b-col>
  
        <!-- Overtime Salary -->
        <b-col>
          <b-form-group
            label="Overtime Salary (price in €uro)"
            label-for="overtime_salary"
          >
            <cleave
              id="overtime"
              v-model="userDataInfo.overtime_salary"
              class="form-control"
              :raw="false"
              :options="options.overtime"
              placeholder="10€"
            />
          </b-form-group>
        </b-col>
              
          </b-row>
        </b-container>
      </b-row>


          <!-- Header: Extra -->
          <div class="d-flex mt-2">
            <feather-icon
              icon="PlusSquareIcon"
              size="19"/>
            <h4 class="mb-0 ml-50">
              Extra
            </h4>
          </div>
  
        <!-- Form: Extra Form -->
        <b-row class="mt-1">
  

          <!-- Field: Bonus (Prime) -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Bonus (Prime)"
              label-for="bonus">
              <v-select
                v-model="selected"
                multiple
                label="title"
                :options="bonus"
              />
            </b-form-group>
          </b-col>

          <!-- Field: Night Amount -->
          <b-col
            cols="12"
            md="6"
            lg="4">
              <b-form-group
                label="Night Amount"
                label-for="nightamount">       
                <v-select
                  v-model="userDataInfo.nightamount"
                  :options="nightOptions"
                  :clearable="false"
                  input-id="night"/>
              </b-form-group>
            </b-col>

          <!-- Field: Transport Amount -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Transport Amount"
              label-for="transportamount">
              <v-select
                  v-model="userDataInfo.transportamount"
                  :options="transportOptions"
                  :clearable="false"
                  input-id="transport"/>
            </b-form-group>
          </b-col>
        </b-row>

          <!-- Header: Bank info -->
          <div class="d-flex mt-2">
            <feather-icon
              icon="HomeIcon"
              size="19"/>
            <h4 class="mb-0 ml-50">
              Bank info
            </h4>
          </div>
  
        <!-- Form: Bank Form -->
        <b-row class="mt-1">
  

          <!-- Field: Bank Name -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Bank Name"
              label-for="bankname">     
            <b-form-input
              id="bankname"
              v-model="userDataInfo.bankname"/>
            </b-form-group>
          </b-col>

          <!-- Field: BIC -->
          <b-col
            cols="12"
            md="6"
            lg="4">
              <b-form-group
                label="BIC"
                label-for="bic">    
                <b-form-input
                  id="bic"
                  v-model="userDataInfo.bic"/>
              </b-form-group>
            </b-col>

          <!-- Field: IBAN -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="IBAN"
              label-for="iban">     
            <b-form-input
              id="iban"
              v-model="userDataInfo.iban"/>
            </b-form-group>
          </b-col>
        </b-row>
  
        <b-row class="mt-2">
          <b-col>
            <b-button
              variant="primary"
              class="mb-1 mb-sm-0 mr-0 mr-sm-1"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
              @click="onSubmit()">
              Save Changes
            </b-button>
            <b-button
              variant="outline-secondary"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
	      @click="reset()"
	    >
              Reset
            </b-button>
          </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
import { BContainer, BRow, BCol, BForm, BFormGroup, BFormInput, BFormRadioGroup, BFormCheckboxGroup, BButton } from 'bootstrap-vue'
import flatPickr from 'vue-flatpickr-component'
import { ref } from '@vue/composition-api'
import vSelect from 'vue-select'
import store from '@/store'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import router from '@/router'
import Cleave from 'vue-cleave-component'


export default {
  components: {
    BContainer,
    BRow,
    BCol,
    BForm,
    BFormGroup,
    flatPickr,
    BFormInput,
    vSelect,
    BFormRadioGroup,
    BFormCheckboxGroup,
    BButton,
    Cleave,
  },
  setup() {
    const userDataInfo = ref({
      monthly_salary:null,
      hourly_salary:null,
      overtime_salary:null,
      weekly_working_time:null,
      bonus: null,
      transport: 'Pass - 50€',
      night: 'Hotel - 150€',
      bankname: null,
      bic: null,
      iban: null,      
    })

    const transportOptions = ['Pass - 50€', 'Train - 60€']

    store.dispatch('app-user/fetchUser', { id: router.currentRoute.params.id })
      .then(response => { 
        console.log(response.data);
        var target = userDataInfo.value;
        target = Object.assign(target, response.data.detail)
        userDataInfo.value.monthly_salary = target.monthly_salary
        userDataInfo.value.hourly_salary = target.hourly_salary
        userDataInfo.value.overtime_salary = target.overtime_salary
        userDataInfo.value.weekly_working_time = target.weekly_working_time
	userDataInfo.value.bankname = target.bankname
        userDataInfo.value.bic = target.bic
        userDataInfo.value.iban = target.iban
        userDataInfo.value.night = target.night
        userDataInfo.value.transport = target.transport
      })
      .catch(error => {
        console.error(error);
      })

    const reset = () => {
       userDataInfo.value.monthly_salary = null
        userDataInfo.value.hourly_salary = null
        userDataInfo.value.overtime_salary = null
        userDataInfo.value.weekly_working_time = null
	userDataInfo.value.bankname = null
        userDataInfo.value.bic = null
        userDataInfo.value.iban = null
        userDataInfo.value.night = null
        userDataInfo.value.transport = null
    }

    const toast = useToast();
    
    const onSubmit = () => {
      store.dispatch('app-user/updateUser', { id: router.currentRoute.params.id, detail_data: userDataInfo.value})
        .then(response => {
          toast({
            component: ToastificationContent,
            props: {
              title: 'Successfully Added',
              icon: 'CheckIcon',
              variant: 'success',
            },
          })
        })
        .catch(error => {
          console.log(error)
        })
    }

    const nightOptions = ['Hotel - 150€', 'AirBnB - 90€']

    return {
      userDataInfo,
      transportOptions,
      nightOptions,
      selected: null,
      bonus: [
        { title: 'Bonus 1' },
        { title: 'Bonus 2' },
        { title: 'Bonus 3' },
      ],
      onSubmit,
      reset
    }
  },
  data() {
    return {
      form: {
      },
      options: {
        time: {
          time: true,
          timePattern: ['h', 'm'],
        },
        monthly: {
          numeral: true,
          numeralThousandsGroupStyle: 'thousand',
        },
        hourly: {
          numeral: true,
          numeralThousandsGroupStyle: 'thousand',
        },
        overtime: {
          numeral: true,
          numeralThousandsGroupStyle: 'thousand',
        },
      },
    }
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-flatpicker.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>