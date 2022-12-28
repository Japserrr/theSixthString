window.onload = () => {
}

function preFillBankAccountCodes(bank) {
    let bankCode = [];
    switch (bank.srcElement.value) {
        case 'ABN-Amro':
            bankCode = ['A','B','N','A'];
            break;
        case 'ASN Bank':
            bankCode = ['A','S','N','B'];
            break;
        case 'Bunq':
            bankCode = ['B','U','N','Q'];
            break;
        case 'ING':
            bankCode = ['I','N','G','B'];
            break;
        case 'Knab':
            bankCode = ['K','N','A','B'];
            break;
        case 'Rabobank':
            bankCode = ['R','A','B','O'];
            break;
        case 'SNS Bank':
            bankCode = ['S','N','S','B'];
            break;
    }

    document.getElementById('bank-account-input-1').value = 'N';
    document.getElementById('bank-account-input-2').value = 'L';
    document.getElementById('bank-account-input-5').value = bankCode[0];
    document.getElementById('bank-account-input-6').value = bankCode[1];
    document.getElementById('bank-account-input-7').value = bankCode[2];
    document.getElementById('bank-account-input-8').value = bankCode[3];
}

function nextBankAccountInputField(inputField) {
    //TODO fix e + and - input
    if (inputField.srcElement.value.length === 0) {
        return;
    }

    const inputFieldId = inputField.srcElement.id;
    const splitInputField = inputFieldId.split('-');
    +splitInputField[3]++;
    const nextInputField = document.getElementById(`bank-account-input-${splitInputField[3]}`);

    if (nextInputField.value.length === 0) {
        nextInputField.focus();
        return;
    }

    while (true) {
        +splitInputField[3]++;
        const nextInputField = document.getElementById(`bank-account-input-${splitInputField[3]}`);
        if (nextInputField.value.length === 0) {
            nextInputField.focus();
            return;
        }
    }
}

function validateForm() {
    let isValid = true;

    const inputFields = [
        {
            name: 'first-name',
            required: true,
            type: 'string',
        },
        {
            name: 'infix',
            required: false,
            type: 'string',
        },
        {
            name: 'last-name',
            required: true,
            type: 'string',
        },
        {
            name: 'email',
            required: true,
            type: 'string',
        },
        {
            name: 'phone-number',
            required: false,
            type: 'number',
        },
        {
            name: 'zip-code',
            required: true,
            type: 'string',
        },
        {
            name: 'street-name',
            required: true,
            type: 'string',
        },
        {
            name: 'house-number',
            required: true,
            type: 'string',
        },
        {
            name: 'city',
            required: true,
            type: 'string',
        },
        {
            name: 'country',
            required: true,
            type: 'string',
        },
        {
            name: 'bank-name',
            required: true,
            type: 'string',
        }
    ];
    inputFields.forEach((inputField) => {
        let throwError = false;
        const inputFieldEl = document.getElementById(`${inputField.name}-input`);

        // TODO ADD zipcode check
        if (
            (inputField.required && inputFieldEl.value.length === 0)
            || (inputField.type === 'string' && typeof inputFieldEl.value !== inputField.type)
            || (inputField.name === 'phone-number' && (typeof Number(inputFieldEl.value) !== inputField.type || inputFieldEl.value.length !== 10))
            || (inputField.name === 'zip-code' && inputFieldEl.value.match(RegExp(/[0-9]{4}[A-Z]{2}/)))
            || (inputField.name === 'country' && inputFieldEl.value === 'Kies een land')
            || (inputField.name === 'bank-name' && inputFieldEl.value === 'Kies een bank')
        ) {
            throwError = true;
        }

        if (throwError) {
            inputFieldEl.classList.remove('is-valid');
            inputFieldEl.classList.add('is-invalid', 'border-danger');
            isValid = false;
        } else {
            inputFieldEl.classList.remove('is-invalid', 'border-danger');
            inputFieldEl.classList.add('is-valid');
        }
    });

    if (!validateBankAccount() || !isValid) {
        document.getElementById('error-message').classList.remove('d-none');
        return;
    }

    document.getElementById("payment-details-form").requestSubmit();
}

/** @return Boolean */
function validateBankAccount() {
    let isValid = true;

    let i = 1;
    while (i <= 18) {
        const inputFieldEl = document.getElementById(`bank-account-input-${i}`);
        const stringInputFields = [1, 2, 5, 6, 7, 8];

        let regex;
        if (stringInputFields.includes(i)) {
            regex = /[A-Z]/;
        } else {
            regex = /[0-9]/;
        }

        if (!inputFieldEl.value.match(RegExp(regex)) || inputFieldEl.value.length === 0) {
            inputFieldEl.classList.remove('border-success');
            inputFieldEl.classList.add('border-danger');
            isValid = false;
        } else {
            inputFieldEl.classList.remove('border-danger');
            inputFieldEl.classList.add('border-success');
        }
        i++;
    }

    return isValid;
}
