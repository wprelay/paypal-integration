import React from "react";
import { useNavigate } from "react-router-dom";
import { Button } from "../ui/button";
import { useLocalState } from "../../zustand/localState";

const GoBackButton = () => {
  const navigate = useNavigate();

  const { localState } = useLocalState();

  const goBack = () => {
    // @ts-ignore
    window.location.href = `?page=${localState.relay_plugin_slug}#settings?settings_tab=payment_settings`; // or navigate('back');
  };

  return (
    <Button onClick={goBack} className="wrp-opacity-50">
      Go Back To WPRelay
    </Button>
  );
};

export default GoBackButton;
