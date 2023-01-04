<template>
    <form-wizard
        color="#7367F0"
        :title="null"
        :subtitle="null"
        shape="square"
        finish-button-text="Submit"
        back-button-text="Previous"
        class="mb-3"
        @on-complete="formSubmitted"
    >
        <!-- location details tab -->
        <tab-content title="Location Details" :before-change="validationForm">
            <validation-observer ref="accountRules" tag="form">
                <b-row>
                    <b-col cols="12" class="mb-2"> </b-col>
                    <b-col md="6">
                        <b-form-group
                            label="Company Name"
                            label-for="companyname"
                        >
                            <validation-provider
                                #default="{ errors }"
                                name="companyname"
                                rules="required"
                            >
                                <b-form-input
                                    id="companyname"
                                    v-model="edit_data.company_name"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <b-col md="6">
                        <b-form-group
                            label="Immatriculation"
                            label-for="immatriculation"
                        >
                            <validation-provider
                                #default="{ errors }"
                                name="immatriculation"
                                rules="required"
                            >
                                <b-form-input
                                    id="immatriculation"
                                    v-model="edit_data.immatriculation"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <b-col md="12">
                        <b-form-group
                            label="Occupational Medicine"
                            label-for="occupationalmedicine"
                        >
                            <auto-suggest-medicine
                                :occupational_medicine="
                                    edit_data.occupational_medicine
                                "
                                ref="occupationalmedicine"
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </validation-observer>
        </tab-content>

        <!-- Location details tab -->
        <tab-content title="Location Info" :before-change="validationFormInfo">
            <validation-observer ref="infoRules" tag="form">
                <b-row>
                    <b-col cols="12" class="mb-2"> </b-col>

                    <b-col md="6">
                        <b-form-group label="Address 1" label-for="address1">
                            <validation-provider
                                #default="{ errors }"
                                name="address1"
                                rules="required"
                            >
                                <b-form-input
                                    id="address1"
                                    v-model="edit_data.address1"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <b-col md="6">
                        <b-form-group label="Address 2" label-for="address2">
                            <validation-provider
                                #default="{ errors }"
                                name="address2"
                                rules="required"
                            >
                                <b-form-input
                                    id="address2"
                                    v-model="edit_data.address2"
                                />
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <b-col md="4">
                        <b-form-group label="Postcode" label-for="postcode">
                            <validation-provider
                                #default="{ errors }"
                                name="postcode"
                                rules="required"
                            >
                                <b-form-input
                                    id="postcode"
                                    v-model="edit_data.postcode"
                                    :state="errors.length > 0 ? false : null"
                                    type="number"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <b-col md="4">
                        <b-form-group label="City" label-for="city">
                            <validation-provider
                                #default="{ errors }"
                                name="city"
                                rules="required"
                            >
                                <b-form-input
                                    id="city"
                                    v-model="edit_data.city"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <b-col md="4">
                        <b-form-group label="Country" label-for="country">
                            <auto-suggest-countries
                                :option="edit_data.country"
                                ref="country"
                            />
                        </b-form-group>
                    </b-col>
                </b-row>
            </validation-observer>
        </tab-content>

        <!-- convention collective  -->
        <tab-content
            title="Convention collective"
            :before-change="validationFormAddress"
        >
            <validation-observer ref="addressRules" tag="form">
                <b-row>
                    <b-col cols="12" class="mb-2"> </b-col>
                    <b-col md="12">
                        <validation-provider
                            #default="{ errors }"
                            name="Votre convention collective :"
                            rules="required"
                        >
                            <b-form-group
                                label="Votre convention collective :"
                                label-for="convention"
                                :state="errors.length > 0 ? false : null"
                            >
                                <v-select
                                    id="convention"
                                    v-model="edit_data.selectedconvention"
                                    :options="conventionName"
                                    @input="selectConvention"
                                    :selectable="
                                        (option) =>
                                            !option.value.includes(
                                                'select_value'
                                            )
                                    "
                                    label="text"
                                />
                                <b-form-invalid-feedback
                                    :state="errors.length > 0 ? false : null"
                                >
                                    {{ errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <b-col md="12">
                        <validation-provider
                            #default="{ errors }"
                            name="Code APE"
                            rules="required"
                        >
                            <b-form-group
                                label="Code APE"
                                label-for="codeAPE"
                                :state="errors.length > 0 ? false : null"
                            >
                                <v-select
                                    id="codeAPE"
                                    v-model="edit_data.selectedcodeAPE"
                                    :options="codeAPE"
                                    :selectable="
                                        (option) =>
                                            !option.value.includes(
                                                'nothing_selected'
                                            )
                                    "
                                    label="text"
                                />
                                <b-form-invalid-feedback
                                    :state="errors.length > 0 ? false : null"
                                >
                                    {{ errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                    </b-col>
                </b-row>
            </validation-observer>
        </tab-content>
    </form-wizard>
</template>

<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import AutoSuggestMedicine from "./AutoSuggestMedicine.vue";
import AutoSuggestCountries from "./AutoSuggestCountries.vue";
import axios from "@axios";

import {
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
} from "bootstrap-vue";
import { required, email } from "@validations";

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
        FormWizard,
        TabContent,
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        vSelect,
        BFormInvalidFeedback,
        // eslint-disable-next-line vue/no-unused-components
        ToastificationContent,
        AutoSuggestMedicine,
        AutoSuggestCountries,
    },
    data() {
        return {
            companyname: "",
            immatriculation: "",
            occupationalmedicine: "",
            address1: "",
            address2: "",
            postcode: "",
            city: "",
            conventionName: null,
            ape: null,
            selectedconvention: "",
            selectedcodeAPE: "",
            required,
            conventionName: [
            ],
            codeAPE: [
            ],
            option: [],
        };
    },
    props: {
        edit_submitconfirm: { type: Function },
        edit_data: {},
    },
    methods: {
        getUserId() {
            return JSON.parse(localStorage.getItem("userData")).id;
        },
        formSubmitted() {
            var result = this.edit_data;
            result.selectedcodeAPE1 = JSON.stringify(result.selectedcodeAPE);
            result.selectedconvention1 = JSON.stringify(
                result.selectedconvention
            );
            if (
                this.$refs.occupationalmedicine.selected &&
                this.$refs.occupationalmedicine.selected.name
            ) {
                result.occupational_medicine =
                    this.$refs.occupationalmedicine.selected.name;
            }
            if (
                this.$refs.country.selected &&
                this.$refs.country.selected.name
            ) {
                result.country = this.$refs.country.selected.name;
            }
            axios
                .post("api/client/setting-location/updatelocation", result)
                .then((res) => {
                    if (res.data.success == true) {
                        this.PopUpNotification(res.data.message, "success");
                        this.edit_submitconfirm();
                    }
                })
                .catch((err) => {});
        },
        validationForm() {
            return new Promise((resolve, reject) => {
                this.$refs.accountRules.validate().then((success) => {
                    if (success) {
                        resolve(true);
                    } else {
                        reject();
                    }
                });
            });
        },
        validationFormInfo() {
            return new Promise((resolve, reject) => {
                this.$refs.infoRules.validate().then((success) => {
                    if (success) {
                        resolve(true);
                    } else {
                        reject();
                    }
                });
            });
        },
        validationFormAddress() {
            return new Promise((resolve, reject) => {
                this.$refs.addressRules.validate().then((success) => {
                    if (success) {
                        resolve(true);
                    } else {
                        reject();
                    }
                });
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
        getConventionApe() {
            axios
                .post("api/client/setting-location/getconvention")
                .then((res) => {
                    var result=res.data.data;
                    let convention=[{ value: "select_value", text: "Select Value" }]
                    result.map((value,index)=>{
                      convention.push({value:value.id.toString(),text:value.convention_collective})
                    })
                    this.conventionName=convention
                });
        },
        selectConvention(e){
          axios
                .post("api/client/setting-location/getape",{code:e.value})
                .then(res=>{
                  var result=res.data.code;
                  let code=[{value: "nothing_selected", text: "Nothing Selected" }];
                  result.map((value,index)=>{
                    code.push({value:value.id.toString(),text:value.code_ape_id})
                  })
                  this.codeAPE=code;
                })
        }
    },
    mounted() {
        this.getConventionApe();
        this.selectConvention(this.edit_data.selectedconvention)
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-autosuggest.scss";
</style>
