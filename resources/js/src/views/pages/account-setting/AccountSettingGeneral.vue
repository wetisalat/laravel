<template>
  <b-card>

    <!-- media -->
    <b-media no-body>
      <b-media-aside>
        <b-link>
          <b-img ref="previewEl" rounded :src="optionsLocal.avatar" height="80" />
        </b-link>
        <!--/ avatar -->
      </b-media-aside>

      <b-media-body class="mt-75 ml-75">
        <!-- upload button -->
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
          @click="$refs.refInputEl.$el.click()">
          Upload
        </b-button>
        <b-form-file ref="refInputEl" v-model="profileFile" accept=".jpg, .png, .gif" :hidden="true" plain
          @input="inputImageRenderer" />
        <!--/ upload button -->

        <!-- reset -->
        <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm" class="mb-75 mr-75">
          Reset
        </b-button>
        <!--/ reset -->
        <b-card-text>Allowed JPG, GIF or PNG. Max size of 800kB</b-card-text>
      </b-media-body>
    </b-media>
    <!--/ media -->

    <!-- form -->
    <b-form class="mt-2">
      <b-row>
        <b-col sm="4">
          <b-form-group label="Company" label-for="account-company">
            <b-form-input v-model="optionsLocal.company" placeholder="Company" name="company" />
          </b-form-group>
        </b-col>
        <b-col sm="4">
          <b-form-group label="SIRET" label-for="account-siret">
            <b-form-input v-model="optionsLocal.siret" placeholder="SIRET" name="siret" />
          </b-form-group>
        </b-col>
        <b-col sm="4">
          <b-form-group label="TVA Intracom" label-for="account-tva_intracom">
            <b-form-input v-model="optionsLocal.tva_intracom" placeholder="TVA Intracom" name="tva_intracom" />
          </b-form-group>
        </b-col>
        <b-col sm="6">
          <b-form-group label="E-mail" label-for="account-email">
            <b-form-input v-model="optionsLocal.email" placeholder="E-mail" name="email" type="email" />
          </b-form-group>
        </b-col>
        <b-col sm="6">
          <b-form-group label="Phone Number" label-for="account-phone_number">
            <b-form-input v-model="optionsLocal.phone_number" type="number" name="phone_number"
              placeholder="Phone Number" />
          </b-form-group>
        </b-col>
        <b-col sm="6">
          <b-form-group label="First Name" label-for="account-first_name">
            <b-form-input v-model="optionsLocal.first_name" name="first_name" placeholder="First Name" />

          </b-form-group>
        </b-col>
        <b-col sm="6">
          <b-form-group label="Last Name" label-for="account-last_name">
            <b-form-input v-model="optionsLocal.last_name" name="last_name" placeholder="Last Name" />
          </b-form-group>
        </b-col>

        <!-- alert -->
        <b-col cols="12" class="mt-75">
          <b-alert show variant="warning" class="mb-50">
            <h4 class="alert-heading">
              {{ }}
            </h4>
            <!-- <div class="alert-body">
              <b-link class="alert-link">
                Resend confirmation
              </b-link>
            </div> -->
          </b-alert>
        </b-col>
        <!--/ alert -->

        <b-col cols="12">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mt-2 mr-1"
            @click.prevent="SaveChange">
            Save changes
          </b-button>
          <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" type="reset" class="mt-2"
            @click.prevent="togetGereralData">
            Reset
          </b-button>
        </b-col>
      </b-row>
    </b-form>
  </b-card>
</template>

<script>
import {
  BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils'
import { ref } from '@vue/composition-api'
import axios from '@axios'

export default {
  components: {
    BButton,
    BForm,
    BImg,
    BFormFile,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
  },
  directives: {
    Ripple,
  },
  props: {
    generalData: {
      type: Object,
      default: () => { },
    },
  },
  data() {
    return {
      optionsLocal: { avatar: '/images/avatar/user_empty.png' },
      profileFile: null,
    }
  },
  methods: {
    resetForm() {
      this.optionsLocal = JSON.parse(JSON.stringify(this.generalData))
    },
    getUserId() {
      return JSON.parse(localStorage.getItem('userData')).id;
    },
    togetGereralData() {
      let user_id = this.getUserId();
      axios.post('/client/account-setting/data', { id: user_id }).then(res => {
        console.log(res, 'this si calling')
        let { email, phone, siret, company, tva_intracom, firstname, lastname, avatar } = res.data.data;
        if (avatar == null) {
          avatar = '/images/avatar/user_empty.png'
        }
        this.optionsLocal = {
          email: email,
          phone_number: phone,
          last_name: lastname,
          first_name: firstname,
          company: company,
          siret: siret,
          tva_intracom: tva_intracom,
          avatar: avatar,
        }
      })
    },
    SaveChange() {
      console.log("SaveChange");
      let user_id = this.getUserId();
      this.optionsLocal.id = user_id;
      axios.post('/client/account-setting/save_change', this.optionsLocal).then(res => {
        if (res.data.success == true) {
          this.PopUpNotification(res.data.msg)
        }
      })
    },
    PopUpNotification(msg) {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: msg,
          icon: 'EditIcon',
          variant: 'success',
        },
      })
    }
  },
  setup() {
    console.log("this is set up")
    const refInputEl = ref(null)
    const previewEl = ref(null)

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, previewEl)

    return {
      refInputEl,
      previewEl,
      inputImageRenderer,
    }
  },
  mounted() {
    this.togetGereralData();
  }
}
</script>
