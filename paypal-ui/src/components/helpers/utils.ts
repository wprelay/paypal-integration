export const handleFields = (State: any, value: any, fields: any) => {
    fields = fields.split(".");
    let data = { ...State };
    let currentState = data;

    fields.forEach((field: any, index: number) => {
        if (index === fields.length - 1) {
            currentState[field] = value === null ? "" : value;
        } else {
            if (currentState[field]) {
                currentState = currentState[field];
            } else {
                console.log(`Field "${field}" not found in State.`)
            }
        }
    });

    return data;
};
