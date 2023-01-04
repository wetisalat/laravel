<template>
  <b-tabs vertical content-class="col-12 col-md-9 mt-1 mt-md-0" pills nav-wrapper-class="col-md-3 col-12"
    nav-class="nav-left">

    <!-- general tab -->
    <b-tab active>

      <!-- title -->
      <template #title>
        <feather-icon icon="UserIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">General</span>
      </template>

      <account-setting-general v-if="options.general" :general-data="options.general" />
    </b-tab>
    <!--/ general tab -->


    <!-- Address tab -->
    <b-tab>

      <!-- title -->
      <template #title>
        <feather-icon icon="HomeIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">Address</span>
      </template>

      <account-setting-address v-if="options.info" :information-data="options.info" />
    </b-tab>

    <!-- Address tab -->
    <b-tab>

      <!-- title -->
      <template #title>
        <feather-icon icon="MapPinIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">Locations</span>
      </template>

      <account-setting-locations />
    </b-tab>

      <!-- Shift tab -->
      <b-tab>
      <!-- title -->
      <template #title>
        <feather-icon icon="ClockIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">Shift</span>
      </template>

      <account-setting-shift />
    </b-tab>
    <!--/ Shift tab -->

    <!-- change password tab -->
    <b-tab>
      <!-- title -->
      <template #title>
        <feather-icon icon="LockIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">Change Password</span>
      </template>

      <account-setting-password />
    </b-tab>
    <!--/ change password tab -->

  </b-tabs>
</template>

<script>
import { BTabs, BTab } from 'bootstrap-vue'
import AccountSettingGeneral from './AccountSettingGeneral.vue'
import AccountSettingAddress from './AccountSettingAddress.vue'
import AccountSettingLocations from './AccountSettingLocations.vue'
import AccountSettingShift from './AccountSettingShift.vue'
import AccountSettingPassword from './AccountSettingPassword.vue'



export default {
  components: {
    BTabs,
    BTab,
    AccountSettingGeneral,
    AccountSettingAddress,
    AccountSettingLocations,
    AccountSettingShift,
    AccountSettingPassword
  },
  data() {
    return {
      options: {},
    }
  },
  beforeCreate() {
    this.$http.get('/account-setting/data').then(res => { this.options = res.data })
  },
}
</script>
