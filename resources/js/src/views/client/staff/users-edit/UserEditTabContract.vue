<template>
  <div>

    <!-- Header: Personal Info -->
    <div class="d-flex">
      <feather-icon
        icon="ClipboardIcon"
        size="19"
      />
      <h4 class="mb-0 ml-50">
        Contract
      </h4>
    </div>

    <!-- Form: Personal Info Form -->
    <b-form class="mt-1">
      <b-row>

        <b-container fluid>
          <b-row>

            <!-- Field: Contract -->
            <b-col>
              <b-form-group
                label="Contract"
                label-for="contract">
                <v-select
                  v-model="userDataInfo.contract"
                  :options="contractOptions"
                  :clearable="false"
                  input-id="contract"/>
              </b-form-group>
            </b-col>

            <!-- Field: Time type -->
            <b-col>
              <b-form-group
                label="Time type"
                label-for="timetype">
                <v-select
                  v-model="userDataInfo.timetype"
                  :options="timetypeOptions"
                  :clearable="false"
                  input-id="timetype"/>
              </b-form-group>
            </b-col>

            <!-- Field: Hiring Date -->
            <b-col>
              <b-form-group
                label="Hiring Date"
                label-for="hiringdate">
                <flat-pickr
                  v-model="userDataInfo.hiringdate"
                  class="form-control"
                  :config="{ dateFormat: 'd-m-Y'}"
                  placeholder="DD-MM-YYYY"/>
              </b-form-group>
            </b-col>

            <!-- Field: End Date -->
            <b-col>
              <b-form-group
                label="End Date"
                label-for="enddate">
                <flat-pickr
                  v-model="userDataInfo.enddate"
                  class="form-control"
                  :config="{ dateFormat: 'd-m-Y'}"
                  placeholder="DD-MM-YYYY"/>
              </b-form-group>
            </b-col>
              
          </b-row>
        </b-container>
      </b-row>


          <!-- Header: Qualification -->
          <div class="d-flex mt-2">
            <feather-icon
              icon="BookOpenIcon"
              size="19"/>
            <h4 class="mb-0 ml-50">
              Qualification
            </h4>
          </div>
  
        <!-- Form: Qualification Form -->
        <b-row class="mt-1">
  
          <!-- Field: Statut -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Statut"
              label-for="statut">
              <v-select
                  v-model="userDataInfo.statut"
                  :options="contractOptions"
                  :clearable="false"
                  input-id="statut"/>
            </b-form-group>
          </b-col>

          <!-- Field: Catégorie professionnelle -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Catégorie professionnelle"
              label-for="categorie">
              <v-select
                  v-model="userDataInfo.categorie"
                  :options="categorieOptions"
                  :clearable="false"
                  input-id="categorie"/>
            </b-form-group>
          </b-col>

          <!-- Field: Echelon -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Echelon"
              label-for="echelon">
              <v-select
                  v-model="userDataInfo.echelon"
                  :options="echelonOptions"
                  :clearable="false"
                  input-id="echelon"/>
            </b-form-group>
          </b-col>

          <!-- Field: Location -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Location"
              label-for="location">
              <v-select
                v-model="selected"
                multiple
                label="title"
                :options="location"
              />
            </b-form-group>
          </b-col>

          <!-- Field: Position -->
          <b-col
            cols="12"
            md="6"
            lg="4">
              <b-form-group
                label="Position"
                label-for="position">       
                <b-form-input
                  id="position"
                  v-model="userDataInfo.position"/>
              </b-form-group>
            </b-col>

          <!-- Field: Shift -->
          <b-col
            cols="12"
            md="6"
            lg="4">
            <b-form-group
              label="Shift"
              label-for="shift">
              <v-select
                v-model="selected2"
                multiple
                label="title"
                :options="shift"
              />
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
	      @click="reset()">
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
import store from '@/store'
import router from '@/router'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'


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
  },
  setup() {
    const userDataInfo = ref({
      contract: 'CDI',
      timetype: 'Temps plein',
      hiringdate: null,
      enddate: null,
      categorie: 'Cadre',
      echelon: null,
      position: 'Manager',
      statut:null

    })

    const contractOptions = ['Apprenti', 'CDD', 'CDD Saisonnier', 'CDI', 'CTT', 'CUI', 'Extra', 'Mise à dispositon', 'Professionnalisation', 'Stage', 'Temporaire']

    const timetypeOptions = ['Temps partiel', 'Temps plein']

    const statutOptions = ['Enployés', 'Agents de maîtrise', 'Ingénieurs et Cadres (IC)', 'Ouvriers']

    const categorieOptions = ['Cadre', 'Non Cadre', 'Ouvriers et employés', 'Techniciens et agents de maîtrise']

    const echelonOptions = ['1', '2', '3', '4', '5', '6', '7', '8', '9']

    store.dispatch('app-user/fetchUser', { id: router.currentRoute.params.id })
      .then(response => { 
        var target = userDataInfo.value;
        target = Object.assign(target, response.data.detail)
        userDataInfo.value.statut = target.statut
        userDataInfo.value.timetype = target.timetype
        userDataInfo.value.hiringdate = target.hiringdate!=null?new Date(target.hiringdate):null;
        userDataInfo.value.enddate = target.enddate!=null?new Date(target.enddate):null;
        userDataInfo.value.categorie = target.categorie
        userDataInfo.value.echelon = target.echelon
        userDataInfo.value.position = target.position
	userDataInfo.value.contract = target.contract
      })
      .catch(error => {
        console.error(error);
      })

    const reset = () => {
        userDataInfo.value.statut = null
        userDataInfo.value.timetype = null
        userDataInfo.value.hiringdate = null;
        userDataInfo.value.enddate = null;
        userDataInfo.value.categorie = null
        userDataInfo.value.echelon = null
        userDataInfo.value.position = null
	userDataInfo.value.contract = null
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


    return {
      userDataInfo,
      contractOptions,
      timetypeOptions,
      statutOptions,
      categorieOptions,
      echelonOptions,
      selected: null,
      location: [
        { title: 'Test 1' },
        { title: 'Test 2' },
        { title: 'Test 3' },
        { title: 'Test 4' },
        { title: 'Test 5' },
      ],
      selected2: null,
      shift: [
        { title: 'Shift 1' },
        { title: 'Shift 2' },
        { title: 'Shift 3' },
        { title: 'Shift 4' },
        { title: 'Shift 5' },
      ],
      onSubmit,
      reset
    }
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-flatpicker.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>