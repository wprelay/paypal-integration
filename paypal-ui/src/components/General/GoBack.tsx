import React from 'react';
import { useNavigate } from 'react-router-dom';
import {Button} from "../ui/button";

const GoBackButton = () => {
    const navigate = useNavigate();

    const goBack = () => {
        window.location.href = '?page=wp-relay#/settings' // or navigate('back');
    };

    return (
        <Button onClick={goBack}>
            Go Back To WPRelay
        </Button>
    );
};

export default GoBackButton;
