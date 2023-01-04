<template>
    <div>
        <b-row>
            <b-col
                md="6"
                v-if="shifts.length > 0"
                v-for="item in shifts"
                :key="item.name"
            >
                <shift :item="item" :reload="reload" />
            </b-col>
            <b-col md="6">
                <b-card no-body class="overflow-hidden">
                    <b-row no-gutters>
                        <b-col md="4" sm="4">
                            <b-card-img
                                src="/images/faq-illustrations.svg"
                                style="width: 85px"
                                alt="Image"
                                class="rounded-0 mt-1"
                            ></b-card-img>
                        </b-col>
                        <b-col md="8" sm="8">
                            <b-card-body>
                                <div class="d-flex justify-content-end">
                                    <b-button
                                        variant="primary"
                                        @click="addNewShift"
                                    >
                                        Add New Shift
                                    </b-button>
                                    <b-modal
                                        v-model="show"
                                        ref="add"
                                        id="modal-shift"
                                        hide-footer
                                        ok-only
                                        ok-title="Shift"
                                        title="Add New Shift"
                                        centered
                                    >
                                        <b-row class="mp-2">
                                            <b-col md="12">
                                                <h2 class="text-center">
                                                    Add New Shift
                                                </h2>
                                                <p class="text-center">
                                                    Add Shift for future timming
                                                </p>
                                            </b-col>
                                            <b-col md="8">
                                                <b-form-group
                                                    label-for="shiftname"
                                                    label="Shift Name"
                                                >
                                                    <b-form-input
                                                        id="shiftname"
                                                        v-model="shift.name"
                                                        placeholder="Shift Name"
                                                    />
                                                </b-form-group>
                                            </b-col>
                                            <b-col md="4">
                                                <b-form-group
                                                    label-for="workingtime"
                                                    label="Working Time"
                                                >
                                                    <cleave
                                                        id="workingtime"
                                                        v-model="shift.workingtime"
                                                        class="form-control"
                                                        :raw="false"
                                                        :options="options.time"
                                                        placeholder="hh:mm"
                                                        readonly
                                                    />
                                                </b-form-group>
                                            </b-col>
                                            <b-col md="4">
                                                <b-form-group
                                                    label-for="starttime"
                                                    label="Start Time"
                                                >
                                                    <b-form-timepicker
                                                        id="starttime"
                                                        placeholder="hh:mm"
                                                        type="time"
                                                        @input="starttime_fn"
                                                        v-model="shift.starttime"
                                                        locale="fr"
                                                    />
                                                </b-form-group>
                                            </b-col>
                                            <b-col md="4">
                                                <b-form-group
                                                    label-for="endtime"
                                                    label="End Time"
                                                >
                                                    <b-form-timepicker
                                                        id="endtime"
                                                        v-model="shift.endtime"
                                                        @input="endtime_fn"
                                                        placeholder="hh:mm"
                                                        type="time"
                                                        locale="fr"
                                                    />
                                                </b-form-group>
                                            </b-col>
                                            <b-col md="4">
                                                <b-form-group
                                                    label-for="breaktime"
                                                    label="Break Time"
                                                >
                                                    <cleave
                                                        id="breaktime"
                                                        v-model="shift.breaktime"
                                                        @input="break_fn"
                                                        class="form-control"
                                                        :raw="false"
                                                        :options="options.time"
                                                        placeholder="hh:mm"
                                                    />
                                                </b-form-group>
                                            </b-col>
                                            <b-col md="12">
                                                <div
                                                    class="d-flex justify-content-center py-1"
                                                >
                                                    <b-button
                                                        variant="primary"
                                                        class="mr-1"
                                                        @click="addShift"
                                                        >Submit</b-button
                                                    >
                                                    <b-button
                                                        variant="outline-primary"
                                                        @click="show = false"
                                                        >Cancel</b-button
                                                    >
                                                </div>
                                            </b-col>
                                        </b-row>
                                    </b-modal>
                                </div>
                                <b-card-text class="text-right mt-2">
                                    Add shift,if it does not exist
                                </b-card-text>
                            </b-card-body>
                        </b-col>
                    </b-row>
                </b-card>
            </b-col>
        </b-row>
        <!-- modal -->
    </div>
</template>

<script>
import {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCardImg,
    BCol,
    BCard,
    BFormTextarea,
    BCardBody,
    BCardText,
    BModal,
    BFormTimepicker,
} from "bootstrap-vue";
import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";
import AutoSuggestCountries from "./AutoSuggestCountries.vue";
import axios from "@axios";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Shift from "./Shift.vue";
import Cleave from "vue-cleave-component";

export default {
    components: {
        BButton,
        BForm,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BCard,
        BCardBody,
        BFormTextarea,
        BCardText,
        BCardImg,
        BModal,
        vSelect,
        flatPickr,
        AutoSuggestCountries,
        Shift,
        BFormTimepicker,
        Cleave,
    },
    data() {
        return {
            address: {},
            shift: {
                name: "",
                starttime: "",
                workingtime: "",
                endtime: "",
                breaktime: "",
                users: [],
            },
            show: false,
            shifts: [],
            options: {
                time: {
                    time: true,
                    timePattern: ["h", "m"],
                },
                working: {
                    time: true,
                    timePattern: ["h", "m"],
                },
            },
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
        getUserId() {
            return JSON.parse(localStorage.getItem("userData")).id;
        },
        loadShift() {
            let id = this.getUserId();
            axios
                .post("api/client/setting-shift/loadshift", { id: id })
                .then((res) => {
                    this.shifts = res.data.data;
                    this.shifts.map((value, index) => {
                        var start_time = value.start_time.split(":");
                        value.start_time1 =
                            start_time[0] + "h" + start_time[1];

                        var end_time = value.end_time.split(":");

                        value.end_time1 = end_time[0] + "h" + end_time[1];
                        value.break_time = value.break_time.replace(":", "h");
                        value.working_time = value.working_time.replace(":", "h");
                        value.users.map((i) => {
                            var firstName = i.name
                                .split(" ")
                                .slice(0, -1)
                                .join(" ");
                            var lastName = i.name
                                .split(" ")
                                .slice(-1)
                                .join(" ");
                            i.nickname =
                                Array.from(firstName)[0].toUpperCase() +
                                " " +
                                Array.from(lastName)[0].toUpperCase();
                        });
                    });
                })
                .catch((err) => {});
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
        addShift() {
            let id = this.getUserId();
            this.shift.id = id;
            axios
                .post("api/client/setting-shift/addshift", this.shift)
                .then((res) => {
                    if (res.data.success == true) {
                        this.PopUpNotification(res.data.message, "success");
                        this.shift = {
                            name: "",
                            starttime: "",
                            workingtime: "",
                            endtime: "",
                            breaktime: "",
                        };
                        this.show = false;
                        this.loadShift();
                    }
                })
                .catch((err) => {
                    this.PopUpNotification(err.response.data.message, "error");
                });
        },
        reload() {
            this.loadShift();
        },
        addNewShift() {
            this.$refs["add"].show();
        },
        starttime_fn(e) {
            if (this.shift.endtime !== "") {
                var value_start = e.split(":");
                var value_end = this.shift.endtime.split(":");
                var result =
                    parseInt(value_end[0] * 60) +
                    parseInt(value_end[1]) -
                    value_start[0] * 60 -
                    value_start[1];
                if (this.shift.breaktime !== "") {
                    var value_break = this.shift.breaktime.split(":");
                    result = result - value_break[0] * 60 - value_break[1];
                }
                if (result > 0) {
                    var result_value = [];
                    result_value[1] = result % 60;
                    result_value[0] = (result - result_value[1]) / 60;
                    if (result_value[0] < 10) {
                        result_value[0] = "0" + result_value[0];
                    }
                    if (result_value[1] < 10) {
                        result_value[1] = "0" + result_value[1];
                    }
                    this.shift.workingtime =
                        result_value[0] + ":" + result_value[1];
                } else {
                    this.shift.workingtime = "Error";
                }
            }
        },
        endtime_fn(e) {
            if (this.shift.starttime !== "") {
                var value_start = this.shift.starttime.split(":");
                var value_end = e.split(":");
                console.log(value_end, value_start);
                var result =
                    parseInt(value_end[0] * 60) +
                    parseInt(value_end[1]) -
                    value_start[0] * 60 -
                    value_start[1];

                if (this.shift.breaktime !== "") {
                    var value_break = this.shift.breaktime.split(":");
                    result = result - value_break[0] * 60 - value_break[1];
                }
                if (result > 0) {
                    var result_value = [];
                    console.log(result, "this is result");
                    result_value[1] = result % 60;
                    result_value[0] = (result - result_value[1]) / 60;
                    if (result_value[0] < 10) {
                        result_value[0] = "0" + result_value[0];
                    }
                    if (result_value[1] < 10) {
                        result_value[1] = "0" + result_value[1];
                    }
                    this.shift.workingtime =
                        result_value[0] + ":" + result_value[1];
                } else {
                    this.shift.workingtime = "Error";
                }
            }
        },
        break_fn(e) {
            if (this.shift.starttime !== "" && this.shift.endtime !== "") {
                var value_start = this.shift.starttime.split(":");
                var value_end = this.shift.endtime.split(":");
                var result =
                    parseInt(value_end[0] * 60) +
                    parseInt(value_end[1]) -
                    value_start[0] * 60 -
                    value_start[1];
                var value_break = e.split(":");
                result = result - value_break[0] * 60 - value_break[1];
                if (result > 0) {
                    var result_value = [];
                    console.log(result, "this is result");
                    result_value[1] = result % 60;
                    result_value[0] = (result - result_value[1]) / 60;
                    if (result_value[0] < 10) {
                        result_value[0] = "0" + result_value[0];
                    }
                    if (result_value[1] < 10) {
                        result_value[1] = "0" + result_value[1];
                    }
                    this.shift.workingtime =
                        result_value[0] + ":" + result_value[1];
                } else {
                    this.shift.workingtime = "Error";
                }
            }
        },
    },
    mounted() {
        this.loadShift();
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
@import "~@resources/scss/vue/libs/vue-flatpicker.scss";
@import "~@resources/scss/vue/libs/vue-autosuggest.scss";
</style>
