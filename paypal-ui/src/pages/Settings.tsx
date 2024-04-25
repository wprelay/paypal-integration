import React, {useState, useEffect} from "react";
import {Card, CardContent, CardHeader, CardTitle} from "../components/ui/card";
import {Button} from "../components/ui/button";
import {ClipLoader} from "react-spinners";
import {override} from "../data/overrride";
import {useLocalState} from "../zustand/localState";
import {axiosClient} from "../components/axios";
import {toastrError, toastrSuccess} from "../ToastHelper";
import {UNPROCESSABLE} from "../data/StatusCodes";
import {settingsType} from "../components/types/settingsType";
import {Label} from "../components/ui/label";
import {Input} from "../components/ui/input"
import {SelectTrigger, Select, SelectItem, SelectContent, SelectValue} from "../components/ui/select";
import {handleFields} from "../components/helpers/utils";


export const Settings = () => {
    const [loading, setLoading] = useState(false);
    const [saveChangesLoading, setSaveChangesLoading] = useState(false)
    const {localState} = useLocalState()
    const [errors, setErrors] = useState<any>()

    const paymentOptions = [
        {
            label: "Standard Payout",
            value: 'standard_payout'
        },
        {
            label: "Mass Payment (Legacy)",
            value: 'mass_payment'
        }
    ]

    const [settings, setSettings] = useState<settingsType>({
        payment_via: "standard_payout",
        client_id: '',
        client_secret: '',
        username: '',
        password: '',
        signature: '',
        subject: '',
    });

    const saveSettings = (e: any) => {
        e.preventDefault();
        setSaveChangesLoading(true)
        setErrors({})
        axiosClient.post(`?action=${localState.ajax_name}`, {
            method: 'save_paypal_settings',
            _wp_nonce_key: 'wpr_paypal_nonce',
            _wp_nonce: localState?.nonces?.wpr_paypal_nonce,
            ...settings
        }).then((response) => {
            toastrSuccess("General Settings Saved Successfully");
            setErrors(null)
        }).catch((error) => {
            let statusCode = error.response.status;

            if (statusCode == UNPROCESSABLE) {
                let errors = error.response.data.data;
                setErrors(errors)
                toastrError('Validation Failed');
                return;
            }
        }).finally(() => {
            setSaveChangesLoading(false)
        })

    }

    useEffect(() => {
        setLoading(true)
        let queryParams: any = {
            method: 'get_general_settings',
            _wp_nonce_key: 'wpr_settings_nonce',
            _wp_nonce: localState?.nonces?.dashboard_nonce,
        };

        const query = '?' + new URLSearchParams(queryParams).toString();

        axiosClient.get(`${query}`).then((response) => {
            let settings = response?.data?.data
            setSettings((prevSettings: any) => ({...prevSettings, settings}))
        }).catch((error) => {
            toastrError('Error Occurred While Fetching General Settings');
        }).finally(() => {
            setLoading(false);
        })
    }, []);

    return <div className='wrp-py-2'>
        <div className='wrp-flex wrp-justify-between wrp-gap-3 wrp-items-center wrp-m-4'>
            <div>
                <span className='wrp-text-xl wrp-leading-5 wrp-font-bold'> Settings</span>
                <i className='wrp wrp-video wrp-text-xl  wrp-text-grayprimary'></i>
            </div>
            <Button
                onClick={saveSettings}
            >
                {saveChangesLoading && (
                    <span className="wrp-mx-2"><ClipLoader color="white" cssOverride={override}
                                                           size={"20px"}/></span>)}
                <span>Save Changes</span>
            </Button>
        </div>

        <Card className='wrp-my-5'>
            <CardContent className='!wrp-p-0'>
                {loading ? (
                    <div className="wrp-w-full wrp-h-96 wrp-flex wrp-flex-row wrp-justify-center wrp-items-center">
                        <div
                        >
                            <ClipLoader cssOverride={override}/>
                        </div>
                    </div>
                ) : (
                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                        <div
                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Transaction Method</h3>
                                <p className='wrp-text-sm wrp-text-grayprimary'>Choose the transaction method </p>
                            </div>
                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                <div className='wrp-flex wrp-flex-col wrp-justify-start wrp-gap-2 wrp-w-full'>
                                    <div className="wrp-flex wrp-justify-start wrp-gap-0 wrp-w-full">
                                        <Select onValueChange={(value: any) => {
                                            setSettings({
                                                ...settings,
                                                payment_via: value,
                                            })
                                        }} defaultValue={settings.payment_via}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Payment Source"/>
                                            </SelectTrigger>
                                            <SelectContent>
                                                {paymentOptions?.map((item: any, index: any) => {
                                                    return <SelectItem value={item.value}
                                                                       key={index}>{item.label}</SelectItem>
                                                })}
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.cookie_duration ? errors.cookie_duration[0] : ''}</p>
                            </div>
                        </div>
                        {
                            settings.payment_via=="standard_payout" && (
                                <>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Client ID</h3>
                                            </div>
                                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                                <div
                                                    className='wrp-flex wrp-flex-col wrp-justify-start wrp-gap-2 wrp-w-full'>
                                                    <div className="wrp-flex wrp-justify-start wrp-gap-0 wrp-w-full">
                                                        <Input id="client_id"
                                                               type="text"
                                                               className='wrp-w-80% wrp-text-primary focus:!wrp-border-none focus:!wrp-shadow-none'
                                                               defaultValue={settings.client_id}
                                                               placeholder="Client ID"
                                                               onChange={(e: any) => {
                                                                   setSettings({...handleFields(settings, e.target.value, 'client_id')});
                                                               }}
                                                        />
                                                    </div>
                                                </div>
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.cookie_duration ? errors.cookie_duration[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Client Secret</h3>
                                            </div>
                                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                                <div
                                                    className='wrp-flex wrp-flex-col wrp-justify-start wrp-gap-2 wrp-w-full'>
                                                    <div className="wrp-flex wrp-justify-start wrp-gap-0 wrp-w-full">
                                                        <Input id="client_secret"
                                                               type="text"
                                                               className='wrp-w-80% wrp-text-primary focus:!wrp-border-none focus:!wrp-shadow-none'
                                                               defaultValue={settings?.client_secret}
                                                               placeholder="Client Secret"
                                                               onChange={(e: any) => {
                                                                   setSettings({...handleFields(settings, e.target.value, 'client_secret')});
                                                               }}
                                                        />
                                                    </div>
                                                </div>
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.cookie_duration ? errors.cookie_duration[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            )
                        }
                        {
                            settings.payment_via=="mass_payment" && (
                                <>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Username</h3>
                                                <p className='wrp-text-sm wrp-text-grayprimary'>Specify the API username associated with your account</p>
                                            </div>
                                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                                <div
                                                    className='wrp-flex wrp-flex-col wrp-justify-start wrp-gap-2 wrp-w-full'>
                                                    <div className="wrp-flex wrp-justify-start wrp-gap-0 wrp-w-full">
                                                        <Input id="username"
                                                               type="text"
                                                               className='wrp-w-80% wrp-text-primary focus:!wrp-border-none focus:!wrp-shadow-none'
                                                               defaultValue={settings?.username}
                                                               placeholder="Username"
                                                               onChange={(e: any) => {
                                                                   setSettings({...handleFields(settings, e.target.value, 'username')});
                                                               }}
                                                        />
                                                    </div>
                                                </div>
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.cookie_duration ? errors.cookie_duration[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Password</h3>
                                                <p className='wrp-text-sm wrp-text-grayprimary'>Specify the password associated with the API user name</p>
                                            </div>
                                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                                <div
                                                    className='wrp-flex wrp-flex-col wrp-justify-start wrp-gap-2 wrp-w-full'>
                                                    <div className="wrp-flex wrp-justify-start wrp-gap-0 wrp-w-full">
                                                        <Input id="password"
                                                               type="password"
                                                               className='wrp-w-80% wrp-text-primary focus:!wrp-border-none focus:!wrp-shadow-none'
                                                               defaultValue={settings?.password}
                                                               placeholder="Password"
                                                               onChange={(e: any) => {
                                                                   setSettings({...handleFields(settings, e.target.value, 'password')});
                                                               }}
                                                        />
                                                    </div>
                                                </div>
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.cookie_duration ? errors.cookie_duration[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Signature</h3>
                                                <p className='wrp-text-sm wrp-text-grayprimary'>If you are using an API signature and not an API certificate, specify the API signature associated with the API username</p>
                                            </div>
                                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                                <div
                                                    className='wrp-flex wrp-flex-col wrp-justify-start wrp-gap-2 wrp-w-full'>
                                                    <div className="wrp-flex wrp-justify-start wrp-gap-0 wrp-w-full">
                                                        <Input id="signature"
                                                               type="text"
                                                               className='wrp-w-80% wrp-text-primary focus:!wrp-border-none focus:!wrp-shadow-none'
                                                               defaultValue={settings?.signature}
                                                               placeholder="Signature"
                                                               onChange={(e: any) => {
                                                                   setSettings({...handleFields(settings, e.target.value, 'signature')});
                                                               }}
                                                        />
                                                    </div>
                                                </div>
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.cookie_duration ? errors.cookie_duration[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Subject</h3>
                                                <p className='wrp-text-sm wrp-text-grayprimary'>If you're calling the API on behalf of a third-party merchant, you must specify the email address on file with PayPal of the third-party merchant or the merchant's account ID (sometimes called Payer ID)</p>
                                            </div>
                                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                                <div
                                                    className='wrp-flex wrp-flex-col wrp-justify-start wrp-gap-2 wrp-w-full'>
                                                    <div className="wrp-flex wrp-justify-start wrp-gap-0 wrp-w-full">
                                                        <Input id="subject"
                                                               type="text"
                                                               className='wrp-w-80% wrp-text-primary focus:!wrp-border-none focus:!wrp-shadow-none'
                                                               defaultValue={settings?.subject}
                                                               placeholder="Subject"
                                                               onChange={(e: any) => {
                                                                   setSettings({...handleFields(settings, e.target.value, 'subject')});
                                                               }}
                                                        />
                                                    </div>
                                                </div>
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.cookie_duration ? errors.cookie_duration[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            )
                        }

                    </div>

                )
                }
            </CardContent>
        </Card>
    </div>
}