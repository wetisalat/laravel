<template>
    <b-card>
        <b-row class="">
            <b-col md="12" class="d-flex justify-content-between">
                <p size="18">Total {{ item.users.length }} Staffs</p>
                <div style="position: relative">
                    <div
                        v-for="(i, key) in item.users"
                        :style="
                            'position: absolute; top:-3px; left:' +
                            (-22 - 15 * key) +
                            'px; z-index:' +
                            key
                        "
                        class="hover"
                    >
                        <b-avatar
                            variant="primary"
                            :id="i.name + i.id"
                            :text="i.nickname"
                            style="border: 1px white solid"
                        ></b-avatar>
                        <b-tooltip :target="i.name + i.id" triggers="hover">
                            {{ i.name }}
                        </b-tooltip>
                    </div>
                </div>
            </b-col>
            <b-col md="12">
                <h3>{{ item.name }}</h3>
            </b-col>
        </b-row>
        <b-row class="">
            <b-col md="6">
                Start time:
                <span
                    ><strong>{{ item.start_time1 }}</strong></span
                >
            </b-col>
            <b-col md="6">
                End time:
                <span
                    ><strong>{{ item.end_time1 }}</strong></span
                >
            </b-col>
        </b-row>
        <b-row>
            <b-col md="6">
                Break time:
                <span
                    ><strong>{{ item.break_time }}</strong></span
                >
            </b-col>
            <b-col md="6">
                Working Time:
                <span
                    ><strong>{{ item.working_time }}</strong></span
                >
            </b-col>
        </b-row>
        <b-row class="">
            <b-col class="d-flex justify-content-between mt-1">
                <small
                    class="text-blue cursor-pointer"
                    style="color: #6d62e4; font-weight: 600"
                    @click="edit(item.id)"
                >
                    Edit Shift
                </small>
                <feather-icon
                    icon="Trash2Icon"
                    class="cursor-pointer"
                    size="20"
                    @click="deleteItem(item.id)"
                />
            </b-col>
        </b-row>
        <b-modal
            v-model="edit_show"
            id="modal-shift"
            ref="edit"
            hide-footer
            ok-only
            ok-title="Shift"
            title="Add New Shift"
            centered
        >
            <b-row class="mp-2">
                <b-col md="12">
                    <h2 class="text-center">Add New Shift</h2>
                    <p class="text-center">Add Shift for future timming</p>
                </b-col>
                <b-col md="8">
                    <b-form-group label-for="shiftname" label="Shift Name">
                        <b-form-input
                            id="shiftname"
                            v-model="edit_shift.name"
                            placeholder="Shift Name"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="4">
                    <b-form-group label-for="workingtime" label="Working Time">
                        <cleave
                            id="workingtime"
                            v-model="edit_shift.workingtime"
                            class="form-control"
                            :raw="false"
                            :options="options.time"
                            placeholder="hh:mm"
                            readonly
                        />
                    </b-form-group>
                </b-col>
                <b-col md="4">
                    <b-form-group label-for="starttime" label="Start Time">
                        <b-form-timepicker
                            id="starttime"
                            @input="starttime_fn"
                            placeholder="hh:mm"
                            type="time"
                            v-model="edit_shift.starttime"
                            locale="fr"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="4">
                    <b-form-group label-for="endtime" label="End Time">
                        <b-form-timepicker
                            id="endtime"
                            v-model="edit_shift.endtime"
                            @input="endtime_fn"
                            placeholder="hh:mm"
                            type="time"
                            locale="fr"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="4">
                    <b-form-group label-for="breaktime" label="Break Time">
                        <cleave
                            id="breaktime"
                            v-model="edit_shift.breaktime"
                            @input="break_fn"
                            class="form-control"
                            :raw="false"
                            :options="options.time"
                            placeholder="hh:mm"
                        />
                    </b-form-group>
                </b-col>
                <b-col md="12">
                    <div class="d-flex justify-content-center py-1">
                        <b-button
                            variant="primary"
                            class="mr-1"
                            @click="editShift"
                            >Submit</b-button
                        >
                        <b-button
                            variant="outline-primary"
                            @click="edit_show = false"
                            >Cancel</b-button
                        >
                    </div>
                </b-col>
            </b-row>
        </b-modal>
        <b-modal
            v-model="delete_modal"
            ref="delete"
            centered
            hide-footer
            title="Delete"
        >
            <div class="d-block text-center">
                <h3>Are you sure?</h3>
            </div>
            <div style="display: flex; justify-content: center">
                <b-button
                    class="mt-3"
                    variant="outline-danger"
                    style="width: 90px"
                    block
                    @click="delete_modal = false"
                    >Cancel</b-button
                >
                <b-button
                    class="mt-3 ml-3"
                    variant="primary"
                    style="width: 75px"
                    block
                    @click="toggleModal"
                    >OK</b-button
                >
            </div>
        </b-modal>
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
    BModal,
    BAvatar,
    BTooltip,
    BFormTextarea,
    BFormTimepicker,
} from "bootstrap-vue";
import axios from "@axios";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Cleave from "vue-cleave-component";

export default {
    components: {
        BCard,
        BRow,
        BCol,
        BButton,
        BModal,
        BFormGroup,
        BFormInput,
        BAvatar,
        BTooltip,
        BFormTimepicker,
        Cleave,
    },
    data() {
        return {
            delete_id: "",
            delete_modal: false,
            edit_modal: false,
            edit_id: "",
            edit_shift: {
                name: "",
                starttime: "",
                endtime: "",
                workingtime: "",
                breaktime: "",
            },
            edit_show: false,
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
    props: {
        item: {},
        reload: { type: Function },
    },
    methods: {
        deleteItem(id) {
            this.delete_id = id;
            this.$refs["delete"].show();
        },
        edit(id) {
            this.edit_id = id;
            axios
                .post("api/client/setting-shift/editshift", {
                    id: this.edit_id,
                })
                .then((res) => {
                    if (res.data.success == true) {
                        this.edit_shift = {
                            name: res.data.data.name,
                            starttime: res.data.data.start_time,
                            endtime: res.data.data.end_time,
                            breaktime: res.data.data.break_time,
                            workingtime: res.data.data.working_time,
                        };
                        this.$refs["edit"].show();
                    }
                });
        },
        editShift() {
            this.edit_shift.id = this.edit_id;
            axios
                .post("api/client/setting-shift/updateshift", this.edit_shift)
                .then((res) => {
                    if (res.data.success == true) {
                        this.PopUpNotification(res.data.message, "success");
                        this.edit_show = false;
                        this.edit_shift = {
                            name: "",
                            starttime: "",
                            endtime: "",
                            workingtime: "",
                            breaktime: "",
                        };
                        this.reload();
                    }
                })
                .catch((error) => {
                    this.PopUpNotification(
                        error.response.data.message,
                        "error"
                    );
                });
        },
        toggleModal() {
            axios
                .post("api/client/setting-shift/deleteshift", {
                    id: this.delete_id,
                })
                .then((res) => {
                    if (res.data.success == true) {
                        this.PopUpNotification(res.data.message, "success");
                        this.reload();
                        this.delete_modal = false;
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
        starttime_fn(e) {
            console.log(this.endtime, "this is endtime");
            if (this.edit_shift.endtime !== "") {
                var value_start = e.split(":");
                var value_end = this.edit_shift.endtime.split(":");
                var result =
                    parseInt(value_end[0] * 60) +
                    parseInt(value_end[1]) -
                    value_start[0] * 60 -
                    value_start[1];
                console.log(
                    this.edit_shift.breaktime,
                    "this.edit_shifts.breaktime"
                );
                if (this.edit_shift.breaktime !== "") {
                    var value_break = this.edit_shift.breaktime.split(":");
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
                    this.edit_shift.workingtime =
                        result_value[0] + ":" + result_value[1];
                } else {
                    this.edit_shift.workingtime = "Error";
                }
            }
        },
        endtime_fn(e) {
            if (this.edit_shift.starttime !== "") {
                var value_start = this.edit_shift.starttime.split(":");
                var value_end = e.split(":");
                console.log(value_end, value_start);
                var result =
                    parseInt(value_end[0] * 60) +
                    parseInt(value_end[1]) -
                    value_start[0] * 60 -
                    value_start[1];
                console.log(
                    this.edit_shift.breaktime,
                    "this.edit_shifts.breaktime"
                );

                if (this.edit_shift.breaktime !== "") {
                    var value_break = this.edit_shift.breaktime.split(":");
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
                    this.edit_shift.workingtime =
                        result_value[0] + ":" + result_value[1];
                } else {
                    this.edit_shift.workingtime = "Error";
                }
            }
        },
        break_fn(e) {
            if (
                this.edit_shift.starttime !== "" &&
                this.edit_shift.endtime !== ""
            ) {
                var value_start = this.edit_shift.starttime.split(":");
                var value_end = this.edit_shift.endtime.split(":");
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
                    this.edit_shift.workingtime =
                        result_value[0] + ":" + result_value[1];
                } else {
                    this.edit_shift.workingtime = "Error";
                }
            }
        },
    },
};
</script>

<style lang="scss">
.cursor-pointer {
    cursor: pointer;
}
.hover {
    transition-duration: 350ms;
}
.badge-primary {
    background-color: #d2ebdf !important;
    color: #62a6e1 !important;
}
.b-avatar {
    width: 24px !important;
    height: 24px !important;
}
.b-avatar-text {
    font-size: 8px !important;
}
.hover:hover {
    z-index: 10001 !important;
    top: -8px !important;
    cursor: pointer;
}
</style>
