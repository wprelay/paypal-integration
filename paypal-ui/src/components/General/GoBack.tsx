import React from 'react';
import { useNavigate } from 'react-router-dom';
import {Button} from "../ui/button";

const GoBackButton = () => {
    const navigate = useNavigate();

    const goBack = () => {
        // @ts-ignore
        window.location.href = '?page=wp-relay#/settings?settings_tab=payment_settings' // or navigate('back');
    };

    return (
        <Button onClick={goBack} className="wrp-opacity-50">
            Go Back To WPRelay
        </Button>
    );
};

export default GoBackButton;
