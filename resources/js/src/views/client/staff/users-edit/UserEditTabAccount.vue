<template>
  <div>

    <!-- Media -->
    <b-media class="mb-2">
      <template #aside>
        <b-avatar
          ref="previewEl"
          :src="userData.avatar"
          :text="avatarText(userData.fullName)"
          :variant="`light-${resolveUserRoleVariant(userData.role)}`"
          size="90px"
          rounded
        />
      </template>
      <h4 class="mb-1">
        {{ userData.fullName }}
      </h4>
      <div class="d-flex flex-wrap">
        <b-button
          variant="primary"
          @click="$refs.refInputEl.click()"
        >
          <input
            ref="refInputEl"
            type="file"
            class="d-none"
            @input="inputImageRenderer"
          >
          <span class="d-none d-sm-inline">Update</span>
          <feather-icon
            icon="EditIcon"
            class="d-inline d-sm-none"
          />
        </b-button>
        <b-button
          variant="outline-secondary"
          class="ml-1"
        >
          <span class="d-none d-sm-inline">Remove</span>
          <feather-icon
            icon="TrashIcon"
            class="d-inline d-sm-none"
          />
        </b-button>
      </div>
    </b-media>

    <!-- User Info: Input Fields -->
    <b-form>
      <b-row>

        <!-- Field: Gender -->
        <b-col
          cols="12"
          md="4"
        >
        <b-form-group
            label="Gender"
            label-for="gender"
          >
            <v-select
              v-model="userData.gender"
              :options="genderOptions"
              :reduce="val => val.value"
              :clearable="false"
              input-id="gender"
            />
          </b-form-group>
        </b-col>

        <!-- Field: First Name -->
        <b-col
          cols="12"
          md="4"
        >
          <b-form-group
            label="First Name"
            label-for="firstname"
          >
            <b-form-input
              id="firstname"
              v-model="userData.firstname"
            />
          </b-form-group>
        </b-col>

        <!-- Field: Last Name -->
        <b-col
          cols="12"
          md="4"
        >
          <b-form-group
            label="Last Name"
            label-for="lastname"
          >
            <b-form-input
              id="lastname"
              v-model="userData.lastname"
            />
          </b-form-group>
        </b-col>

        <!-- Field: Email -->
        <b-col
          cols="12"
          md="4"
        >
          <b-form-group
            label="Email"
            label-for="email"
          >
            <b-form-input
              id="email"
              v-model="userData.email"
              type="email"
            />
          </b-form-group>
        </b-col>

        <!-- Field: Phone Number -->
        <b-col
          cols="12"
          md="4"
        >
          <b-form-group
            label="Phone Number"
            label-for="phone"
          >
            <cleave
              id="phonenumber"
              v-model="userData.phone"
              class="form-control"
              :raw="false"
              :options="options.phonenumber"
              placeholder="+33 6 12 34 56 78"
            />
          </b-form-group>
        </b-col>

        <!-- Field: Role -->
        <b-col
          cols="12"
          md="4"
        >
          <b-form-group
            label="User Role"
            label-for="role"
          >
            <v-select
              v-model="userData.role"
              label="name"
              :options="groups"
              :reduce="val => val.id"
              :clearable="false"
              input-id="user-role"
            />
          </b-form-group>
          <feather-icon
            v-b-modal.group-modal
            icon="PlusCircleIcon"
            size="18"
          />
        </b-col>

      </b-row>
    </b-form>

    <!-- PERMISSION TABLE -->
    <b-card
      no-body
      class="border mt-1"
    >
      <b-card-header class="p-1">
        <b-card-title class="font-medium-2">
          <feather-icon
            icon="LockIcon"
            size="18"
          />
          <span class="align-middle ml-50">Permission</span>
        </b-card-title>
      </b-card-header>
      <b-table
        striped
        responsive
        class="mb-0"
        :items="permissionsData"
      >
        <template #cell(module)="data">
          {{ data.value }}
        </template>
        <template #cell()="data">
          <b-form-checkbox :checked="data.value" />
        </template>
      </b-table>
    </b-card>

    <!-- Action Buttons -->
    <b-button
      variant="primary"
      class="mb-1 mb-sm-0 mr-0 mr-sm-1"
      :block="$store.getters['app/currentBreakPoint'] === 'xs'"
      @click="onSubmit()"
    >
      Save Changes
    </b-button>
    <b-button
      variant="outline-secondary"
      type="reset"
      @click="reset()"
      :block="$store.getters['app/currentBreakPoint'] === 'xs'"
    >
      Reset
    </b-button>

    <b-modal
      id="group-modal"
      ref="group.modal"
      title="Add New Group"
      ok-title="submit"
      cancel-variant="outline-secondary"
      hide-footer
    >
      <validation-observer
        #default="{ handleSubmit }"
        ref="groupForm"
      >
        <b-form
          class="p-2"
          @submit.prevent="handleSubmit(addGroup)"
        >
          <validation-provider
            #default="{ errors }"
            name="Group Name"
            rules="required"
          >
            <b-form-group
              label="Group Name"
              label-for="group-name"
            >
              <b-form-input
                id="group-name"
                v-model="groupName"
                autofocus
                :state="errors.length > 0 ? false:null"
                trim
                placeholder=""
              />

              <b-form-invalid-feedback>
                {{ errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          <div class="text-right">
            <b-button
              class="mt-2"
              variant="outline-secondary"
              @click="hideModal"
            >Cancel</b-button>
            <b-button
              type="submit"
              class="mt-2"
              variant="outline-primary"
            >Submit</b-button>
          </div>
        </b-form>
      </validation-observer>
    </b-modal>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'

import {
  BButton,
  BMedia,
  BAvatar,
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BForm,
  BTable,
  BCard,
  BCardHeader,
  BCardTitle,
  BFormCheckbox,
  BModal,
  VBModal,
  BFormInvalidFeedback,
} from 'bootstrap-vue'
import { avatarText } from '@core/utils/filter'
import vSelect from 'vue-select'
import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils'
import { ref } from '@vue/composition-api'
import store from '@/store'
import router from '@/router'
import Cleave from 'vue-cleave-component'
import { useToast } from 'vue-toastification/composition'

// eslint-disable-next-line import/no-extraneous-dependencies
import 'cleave.js/dist/addons/cleave-phone.fr'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

import useUsersList from '../users-list/useUsersList'

export default {
  components: {
    BButton,
    BMedia,
    BAvatar,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BForm,
    BTable,
    BCard,
    BCardHeader,
    BCardTitle,
    BFormCheckbox,
    BModal,
    BFormInvalidFeedback,
    vSelect,
    Cleave,
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    'b-modal': VBModal,
  },
  props: {
    userData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      groups: [],
      groupName: '',

      // validations
      required,
    }
  },
  setup(props) {
    const { resolveUserRoleVariant } = useUsersList()

    const roleOptions = [
      { label: 'Admin', value: 'admin' },
      { label: 'Author', value: 'author' },
      { label: 'Editor', value: 'editor' },
      { label: 'Maintainer', value: 'maintainer' },
      { label: 'Subscriber', value: 'subscriber' },
    ]

    const genderOptions = [
      { label: 'Male', value: 'male' },
      { label: 'Female', value: 'female' },
      { label: 'Other', value: 'other' },
    ]

    const permissionsData = [
      {
        module: 'Admin',
        read: true,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'Staff',
        read: false,
        write: true,
        create: false,
        delete: false,
      },
      {
        module: 'Author',
        read: true,
        write: false,
        create: true,
        delete: false,
      },
      {
        module: 'Contributor',
        read: false,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'User',
        read: false,
        write: false,
        create: false,
        delete: true,
      },
    ]

    // ? Demo Purpose => Update image on click of update
    const refInputEl = ref(null)
    const previewEl = ref(null)

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, base64 => {
      // eslint-disable-next-line no-param-reassign
      props.userData.avatar = base64
    })

    props.userData.avatar = "../../../" + props.userData.avatar

    const toast = useToast()

    const onSubmit = () => {
      store.dispatch('app-user/updateUser', { id: router.currentRoute.params.id, detail_data: props.userData })
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

    const reset = () => {
      props.userData.gender = null;
      props.userData.firstname = null;
      props.userData.lastname = null;
      props.userData.phone = null;
      props.userData.email = null;
      props.userData.avatar = null;
    }

    return {
      resolveUserRoleVariant,
      avatarText,
      roleOptions,
      genderOptions,
      permissionsData,

      //  ? Demo - Update Image on click of update button
      refInputEl,
      previewEl,
      inputImageRenderer,
      form: {
      },
      options: {
        phonenumber: {
          prefix: '+33',
          phone: true,
          blocks: [2, 2, 2, 2, 2],
        },
      },

      onSubmit,
      reset,
    }
  },
  mounted() {
    this.$store.dispatch('app-user/fetchGroups')
      .then(response => {
        this.groups = response.data.groups
      })
  },
  methods: {
    addGroup() {
      this.$refs.groupForm.validate().then(success => {
        if (success) {
          // Add group API
          this.$store.dispatch('app-user/addGroup', this.groupName).then(() => {
            this.$store.dispatch('app-user/fetchGroups')
              .then(response => {
                this.groups = response.data.groups
                this.hideModal()
              })
          })
        }
      })
    },
    hideModal() {
      this.$refs['group.modal'].hide()
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>
