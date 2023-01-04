<template>
    <b-card>
        <!-- form -->
        <b-form>
            <b-row>
                <!-- address1 -->
                <b-col md="6">
                    <b-form-group label-for="address1" label="Address 1">
                        <b-form-input
                            id="address1"
                            v-model="address.address1"
                        />
                    </b-form-group>
                </b-col>
                <!--/ address1 -->

                <!-- address2 -->
                <b-col md="6">
                    <b-form-group label-for="address2" label="Address 2">
                        <b-form-input
                            id="address2"
                            v-model="address.address2"
                        />
                    </b-form-group>
                </b-col>
                <!--/ address2 -->

                <!-- postcode -->
                <b-col md="4">
                    <b-form-group label-for="postcode" label="Postcode">
                        <b-form-input
                            id="postcode"
                            v-model="address.postcode"
                        />
                    </b-form-group>
                </b-col>
                <!--/ postcode -->

                <!-- city -->
                <b-col md="4">
                    <b-form-group label-for="city" label="city">
                        <b-form-input id="city" v-model="address.city" />
                    </b-form-group>
                </b-col>
                <!--/ city -->
                <!-- Country -->
                <b-col md="4">
                    <b-form-group label-for="countryList" label="Country">
                        <auto-suggest-countries
                            ref="countryData"
                            :option="option"
                        />
                    </b-form-group>
                </b-col>
                <!--/ Country -->

                <b-col cols="12">
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        class="mt-1 mr-1"
                        @click="SaveChange"
                    >
                        Save changes
                    </b-button>
                    <b-button
                        v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                        type="reset"
                        class="mt-1"
                        variant="outline-secondary"
                        @click.prevent="reset"
                    >
                        Reset
                    </b-button>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BFormTextarea,
} from "bootstrap-vue";
import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";
import AutoSuggestCountries from "./AutoSuggestCountries.vue";
import axios from "@axios";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BButton,
        BForm,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BCard,
        BFormTextarea,
        vSelect,
        flatPickr,
        AutoSuggestCountries,
    },
    data() {
        return {
            address: {},
            option: "",
        };
    },
    directives: {
        Ripple,
    },
    props: {
        informationData: {
            type: Object,
            default: () => {},
        },
    },
    methods: {
        resetForm() {},
        getAddress() {
            let id = this.getUserId();
            axios
                .post("api/client/setting-address/getaddress", { id: id })
                .then((res) => {
                    const { address1, address2, postcode, city, country } =
                        res.data.data;
                    this.address = {
                        address1: address1,
                        address2: address2,
                        postcode: postcode,
                        city: city,
                        country: country,
                    };
                    this.option = country;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getUserId() {
            return JSON.parse(localStorage.getItem("userData")).id;
        },
        SaveChange() {
            let id = this.getUserId();
            this.address.id = id;
            console.log(this.$refs.countryData);
            if (
                this.$refs.countryData.selected &&
                this.$refs.countryData.selected.name
            ) {
                this.address.country = this.$refs.countryData.selected.name;
            }
            axios
                .post("api/client/setting-address/save_change", this.address)
                .then((res) => {
                    if (res.data.success == true) {
                        this.PopUpNotification(res.data.message, "success");
                        this.getAddress();
                    }
                });
        },
        PopUpNotification(msg, type) {
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: msg,
                    icon: "EditIcon",
                    variant: type,
                },
            });
        },
        reset() {
            this.address = {
                address1: "",
                address2: "",
                postcode: "",
                city: "",
                country: "",
            };
            this.option = "";
        },
    },
    mounted() {
        this.getAddress();
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
@import "~@resources/scss/vue/libs/vue-flatpicker.scss";
@import "~@resources/scss/vue/libs/vue-autosuggest.scss";
</style>
