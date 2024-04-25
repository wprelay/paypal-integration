import React from "react";
import {Card} from "../components/ui/card";
import {axiosClient} from "../components/axios";
import {toastrError} from "../ToastHelper";
import {useLocalState} from "../zustand/localState";


export const BatchPayoutItem = () => {
    const {localState} = useLocalState();

    const getItems = () => {
        axiosClient.get('?action=wp_relay_paypal', {
            params: {
                method: 'paypal_batch_item_list',
                _wp_nonce_key: 'wpr_paypal_nonce',
                _wp_nonce: localState?.nonces?.wpr_paypal_nonce,
            },

        }).then((response) => {
            console.log(response)
        }).catch(response => {
            toastrError('Error Occurred')
        })
    }

    React.useEffect(() => {
        getItems();
    }, [])

    return <Card>
        <div>

        </div>
    </Card>
}