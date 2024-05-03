import React, {useEffect, useState} from "react";
import {Card, CardContent} from "../components/ui/card";
import {Button} from "../components/ui/button";
import {ClipLoader} from "react-spinners";
import {override} from "../data/overrride";
import {useLocalState} from "../zustand/localState";
import {axiosClient} from "../components/axios";
import {toastrError, toastrSuccess} from "../ToastHelper";
import {UNPROCESSABLE} from "../data/StatusCodes";
import {settingsType} from "../components/types/settingsType";
import {Input} from "../components/ui/input"
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from "../components/ui/select";
import {handleFields} from "../components/helpers/utils";
import {Popover, PopoverContent, PopoverTrigger} from "@radix-ui/react-popover";


export const Settings = () => {
    const [loading, setLoading] = useState(false);
    const [saveChangesLoading, setSaveChangesLoading] = useState(false)
    const {localState} = useLocalState()
    const [errors, setErrors] = useState<any>()
    const [urlCopied, setUrlCopied] = useState<boolean>(false)
    const paymentOptions = [
        {
            label: "Payouts REST API (Latest)",
            value: 'latest'
        },
        {
            label: "Legacy API (SOAP)",
            value: 'legacy'
        }
    ]

    const [settings, setSettings] = useState<settingsType>({
        payment_via: "latest",
        client_id: '',
        client_secret: '',
        username: '',
        password: '',
        signature: '',
    });

    const saveSettings = (e: any) => {
        e.preventDefault();
        setSaveChangesLoading(true)
        if (settings.payment_via == "latest") {
            setErrors({})
            if (settings.client_id == '') {
                setErrors((prevErrors: any) => ({...prevErrors, client_id: ['Client ID is required']}))
            }
            if (settings.client_secret == '') {
                setErrors((prevErrors: any) => ({...prevErrors, client_secret: ['Client Secret key is required']}))
            }
            if (settings.client_id == "" || settings.client_secret == "") {
                setSaveChangesLoading(false)
                return;
            }
        }
        if (settings.payment_via == "legacy") {
            setErrors({})
            if (settings.username == '') {
                setErrors((prevErrors: any) => ({...prevErrors, username: ['Username is required']}))
            }
            if (settings.password == '') {
                setErrors((prevErrors: any) => ({...prevErrors, password: ['Password is required']}))
            }
            if (settings.signature == '') {
                setErrors((prevErrors: any) => ({...prevErrors, signature: ['Signature is required']}))
            }

            if (settings.username == "" || settings.password == "" || settings.signature == "") {
                setSaveChangesLoading(false)
                return;
            }
        }
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

    const copyReferralURL = async (e: any) => {
        // @ts-ignore
        if ('clipboard' in navigator) {
            // @ts-ignore
            await navigator.clipboard.writeText(affiliate.url);
        } else {
            // @ts-ignore
            document.execCommand('copy', true, affiliate.url);
        }

        setUrlCopied(true);

        setTimeout(() => {
            setUrlCopied(false)
        }, 2000)
    }

    const fetchSettings = () => {
        setLoading(true)
        let queryParams: any = {
            action: localState.ajax_name,
            method: 'get_paypal_settings',
            _wp_nonce_key: 'wpr_paypal_nonce',
            _wp_nonce: localState?.nonces?.wpr_paypal_nonce,
        };

        const query = '?' + new URLSearchParams(queryParams).toString();

        axiosClient.get(`${query}`).then((response) => {
            let data = response?.data?.data
            console.log(data)
            setSettings(data)
        }).catch((error) => {
            toastrError('Error Occurred While Fetching General Settings');
        }).finally(() => {
            setLoading(false);
        })
    }

    useEffect(() => {
        fetchSettings();
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
                            </div>
                        </div>
                        {
                            settings.payment_via == "latest" && (
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
                                                        <Input
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
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.client_id ? errors.client_id[0] : ''}</p>
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
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.client_secret ? errors.client_secret[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Webhook
                                                    Configuration</h3>
                                            </div>
                                            <div className="wrp-flex-1 wrp-flex-row wrp-gap-1">
                                                <div
                                                    className='wrp-flex wrp-justify-start wrp-gap-2 wrp-w-full'>
                                                    <div>
                                                        <span>Copy this</span>
                                                    </div>
                                                    <Popover>
                                                        <PopoverTrigger className='wrp-flex '>
                                                            <i onClick={() => {
                                                                copyReferralURL("text")
                                                            }}
                                                               className='wpr wpr-copy lg:wrp-text-lg wrp-text-4 wrp-cursor-pointer'></i>
                                                        </PopoverTrigger>
                                                        <PopoverContent align='start'
                                                                        className='!wrp-w-20 wrp-flex !wrp-h-10 wrp-duration-500  '>
                                                            <p className='wrp-flex wrp-justify-center wrp-items-center'>Copied</p>
                                                        </PopoverContent>
                                                    </Popover>
                                                </div>
                                                <p className="!wrp-text-sm wrp-pt-1.5">note : Lorem
                                                    ipsum dolor sit amet,</p>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            )
                        }
                        {
                            settings.payment_via == "legacy" && (
                                <>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Username</h3>
                                                <p className='wrp-text-sm wrp-text-grayprimary'>Specify the API username
                                                    associated with your account</p>
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
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.username ? errors.username[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Password</h3>
                                                <p className='wrp-text-sm wrp-text-grayprimary'>Specify the password
                                                    associated with the API user name</p>
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
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.password ? errors.password[0] : ''}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="wrp-w-full wrp-flex wrp-flex-col">
                                        <div
                                            className="wrp-flex wrp-flex-row wrp-py-10 wrp-border-b-1 wrp-rounded wrp-px-6 ">
                                            <div className="wrp-flex-1 wrp-flex wrp-flex-col wrp-gap-2">
                                                <h3 className='wrp-text-4 wrp-font-bold wrp-leading-5'>Signature</h3>
                                                <p className='wrp-text-sm wrp-text-grayprimary'>If you are using an API
                                                    signature and not an API certificate, specify the API signature
                                                    associated with the API username</p>
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
                                                <p className=" wrp-text-xs wrp-text-destructive wrp-pt-1.5">{errors?.signature ? errors.signature[0] : ''}</p>
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