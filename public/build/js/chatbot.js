"use strict";

(function () {


    // ******************************************************************************

    const chatContainer = document.getElementById("chatContainer");
    const userInput = document.getElementById("userInput");
    const sendButton = document.getElementById("sendButton");

    let initialQuestion = 1;
    let endQuestion = 4;
    let arrayResponse = [];
    let arrayResponses = [];
    let arrayQuestion = [];
    // let currentTypeDoc = [];

    let arrayDocument = [];

    // Iniciar el flujo del chatbot
    getQuestion(initialQuestion);

    const type_document = {
        currentValue: 0
    }

    const isMessageResponse = {
        flag: false
    }

    /******************************************************  Fetch API *********************************** */


    async function getQuestion(questionId) { // Función para obtener la pregunta y opciones desde el servidor

        try {

            // const apiUrl = buildUrl("api/question", { id: questionId });
            const apiUrl = `api/question?id=${questionId}`;

            const response = await fetch(apiUrl, {
                method: 'GET',
                mode: 'cors',
                credentials: 'include'
            });

            const data = await response.json();

            if (data.status) {
                console.log(data);
                fillQuestion(data);
                fillResponses(data);
                updateFlagShowMessage(false);
                displayBotMessage(data.question.question_text);
                displayResponses(data.responses);
            } else {
                console.log(data.message);
                displayBotMessage(data.question)
            }

        } catch (error) {
            console.log("Ocurrio un error en la peticion: ", error);
        }
    }


    async function getResponse(responseId) { // Funcion para obtener la respuesta del usuario.

        try {

            // const apiUrl = buildUrl("api/responses", { id: responseId });
            const apiUrl = `api/responses?id=${responseId}`;



            const response = await fetch(apiUrl, {
                method: 'GET',
                mode: 'cors',
                credentials: 'include'
            });

            const data = await response.json();

            // return data;

            if (data.status) {
                console.log(data);
                fillResponse(data);
                updateFlagShowMessage(true);
                // displayBotMessage(data.response.response_text);
                displayResponses([data.response]);
            } else {
                console.log(data.message);
            }

        } catch (error) {
            console.log("Ocurrio un error en la peticion: ", error);
        }
    }


    async function createPDF(type, responseId) { // Funcion para crear el PDF
        try {

            const formData = new URLSearchParams();
            formData.append("type", type);
            formData.append("responseId", responseId);

            // const apiUrl = buildUrl("api/document/create");
            const apiUrl = `api/document/create`;

            const response = await fetch(apiUrl, {
                method: 'POST',
                credentials: 'include',
                body: formData
            });

            return await response.json();

        } catch (error) {
            console.error('Mensaje de error desde crear pdf', error);
            return null;
        }
    }


    async function downloadPDF(documentId) { // Funcion para descargar el pdf.

        try {

            // const apiUrl = buildUrl("api/document/download", { documentId: documentId });
            const apiUrl = `api/document/download?id=${documentId}`;

            const response = await fetch(apiUrl, {
                method: 'GET',
                mode: 'cors',
                credentials: 'include'
            });

            // console.log(await response.json());
            // return

            // const blob = await response.blob();
            // return window.URL.createObjectURL(blob);

            return await response.json();

            // const url = window.URL.createObjectURL(blob);


        } catch (error) {
            console.error('Mensaje de error desde crear pdf', error);
            // return null;
        }
    }


    async function sendEmail(documentId) { // Función para obtener la pregunta y opciones desde el servidor

        try {

            // const apiUrl = buildUrl("api/document/send", { documentId: documentId });
            const apiUrl = `api/document/send?id=${documentId}`;

            const response = await fetch(apiUrl, {
                method: 'GET',
                mode: 'cors',
                credentials: 'include'
            });

            return await response.json();

        } catch (error) {
            return null;
            // alertify.error(showMessage('serverError'));
        }
    }


    /*************************************************** Utilidades  ********************************************************** */


    function fillQuestion(data) { // Llena el arrayQuestion con la pregunta actual y las respuestas que pertenecen a la pregunta.
        arrayQuestion.length = 0;
        arrayQuestion = structuredClone(data);
    }


    function fillResponses(data) { // Llena el arrayResponses con las preguntas de la respuesta actual con el fin de separar las respuestas para acceder mas facilmente.
        arrayResponses.length = 0;
        arrayResponses = structuredClone(data.responses);
    }


    function fillResponse(data) { // Llena el arrayResponse con la respuesta actual obtenida de getReponse();
        arrayResponse.length = 0;
        arrayResponse = structuredClone(data.response);
    }


    function fillDocument(data) { // Llena el arrayDocument con el id del documento recien generado para poder acceder a este.
        arrayDocument.length = 0;
        arrayDocument = structuredClone(data);
    }


    function updateFlagShowMessage(flag) {
        isMessageResponse.flag = flag;
    }


    function updateTypeDoc(newValue) {
        if (newValue !== null) {
            type_document.currentValue = newValue;
        }
    }

    const dictionaryTypesDocuments = new Map([
        [1, "Constancia Salarial"],
        [2, "Boleta de pago"],
        [3, "Constancia de trabajo"]

    ]);




    /******************************************************  Manejar logica *********************************** */



    async function handleUserSelection(opcion) { // Manejador seleccion del usuario

        let has_next_question = getIndex(opcion, arrayResponses)?.has_next_question;
        let has_next_response = getIndex(opcion, arrayResponses)?.has_next_response;
        let has_document = getIndex(opcion, arrayResponses)?.has_document;
        let response_types_id = getIndex(opcion, arrayResponses)?.response_types_id;

        let next_question_id = getIndex(opcion, arrayResponses)?.next_question_id; //
        let parent_response_id = getIndex(opcion, arrayResponses)?.parent_response_id; //
        let document_types_id = getIndex(opcion, arrayResponses)?.document_types_id; //
        let status = getIndex(opcion, arrayResponses)?.status; //
        let get_document = getIndex(opcion, arrayResponses)?.get_document; //
        let type_doc = getIndex(opcion, arrayResponses)?.type_document;

        let response_text = getIndex(opcion, arrayResponses)?.response_text; //Obtener la opcion actual.
        let selected_response_id = getIndex(opcion, arrayResponses)?.id; //Me devuelve el id de la opcion seleccionada
        let question_id = getIndex(opcion, arrayResponses)?.question_id; //Me devuelve el question_id de la opcion seleccionada

        if (document_types_id !== null) { // Si type_doc es numero dejar pasar.
            updateTypeDoc(document_types_id);
        }

        switch (response_types_id) {
            case 1:

                if (has_next_question === 1) {
                    console.log('has_next_question', has_next_question);

                    displayUserMessage(response_text); //Muestra la opcion selecciona del usuario.
                    clearInput(); // Limpia el input.
                    await getQuestion(next_question_id);
                }

                if (has_next_response === 1) {
                    console.log('has_next_response', has_next_response);

                    displayUserMessage(response_text); //Muestra la opcion selecciona del usuario.
                    clearInput(); // Limpia el input.
                    await getResponse(selected_response_id);

                    if (arrayResponse.status === 0) { //Se ejecuta si la respuesta esta completa con 0 sino muestra ultima pregunta para finalizar el chat

                        do {  //Hacer mientras (Llama las respuestas mientras exista una proxima respuesta).
                            await getResponse(arrayResponse.id);
                            has_next_response = arrayResponse.has_next_response;
                            console.log(" has_next_response inner loop", has_next_response);
                        } while (has_next_response === 1 && arrayResponse.status === 0);
                    } else {
                        await getQuestion(endQuestion);
                    }

                    if (arrayResponse.has_next_question === 1) {
                        console.log('arrayResponse.has_next_question', arrayResponse.has_next_question);
                        await getQuestion(arrayResponse.next_question_id);
                    }
                }
                break;

            case 2:

                if (has_next_question === 1) {
                    displayUserMessage(response_text); //Muestra la opcion selecciona del usuario.
                    clearInput(); // Limpia el input.
                    await getQuestion(next_question_id);
                }

                if (has_next_response === 1) {
                    displayUserMessage(response_text);
                    clearInput();
                }

                if (isNumber(document_types_id)) {
                    if (type_document.currentValue > 0) {
                        const response = await createPDF(type_document.currentValue, selected_response_id);
                        fillDocument(response);
                        console.log(arrayDocument);
                    }
                }

                if (type_document.currentValue === 1) {
                    if (has_next_question === 0 && has_next_response === 0) {
                        displayUserMessage(response_text);
                        clearInput();

                        switch (type_document.currentValue) {
                            case 1:
                                const response = await downloadPDF(arrayDocument.id); // Imprime el nombre del documento?
                                if (get_document === 0) {
                                    displayBotDownloadLink(response.url, response.filename)
                                    await getQuestion(endQuestion);
                                } else {
                                    const response = await sendEmail(arrayDocument.id); //Este es el id de la respuesta como tal..
                                    displayBotMessage(response.message);
                                    await getQuestion(endQuestion);
                                }

                                break;

                            default:
                                break;
                        }


                    }
                }


                break;

            default:
                break;
        }


        // if (getIndex(opcion, arrayResponses)?.type_response === 1) {
        //     if (next_question && next_question !== null) { // Si hay siguiente respuesta
        //         console.log('Si hay una siguiente pregunta');

        //         displayUserMessage(response_text); //Muestra la opcion selecciona del usuario.
        //         clearInput(); // Limpia el input.

        //         await getQuestion(next_question); // Obtener el nuevo bloque de pregunta y respuestas.


        //     } else { // No hay siguiente respuesta entonces verificar las respuestas.
        //         console.log('No hay una siguiente pregunta pero si una respuesta');

        //         if (next_response && next_response === 1) { //Evalua que exista y que tenga una secuencia respuesta.
        //             console.log('Si hay una respuesta y una secuencia');

        //             displayUserMessage(response_text);
        //             clearInput();

        //             await getResponse(response_id);

        //             if (arrayResponse.next_response !== null) {

        //                 do {  //Hacer mientras (Llama las respuestas mientras exista una proxima pregunta).
        //                     await getResponse(arrayResponse.id);
        //                     next_response = arrayResponse.next_response;
        //                     console.log(" next_response ", next_response);
        //                 } while (next_response === 1);
        //             } else {
        //                 console.log("Ha habido un error aca: Si hay una respuesta y una secuencia");
        //             }

        //             if (arrayResponse.next_question && arrayResponse.next_question !== null) {
        //                 await getQuestion(arrayResponse.next_question);
        //             }

        //         } else {
        //             console.log('No hay proxima pregunta pero Si hay una respuesta y no secuencia');

        //         }

        //     }
        // }


        // if (getIndex(opcion, arrayResponses)?.type_response === 2) {



        // }


        // if (getIndex(opcion, arrayResponses)?.type_response === 3) {
        //     if (next_question && next_question !== null) { // Si hay siguiente respuesta
        //         console.log('Si hay una siguiente pregunta');

        //         displayUserMessage(response_text); //Muestra la opcion selecciona del usuario.
        //         clearInput(); // Limpia el input.

        //         await getQuestion(next_question); // Obtener el nuevo bloque de pregunta y respuestas.


        //     } else { // No hay siguiente respuesta entonces verificar las respuestas.
        //         console.log('No hay una siguiente pregunta pero si una respuesta');

        //         if (next_response && next_response === 1) { //Evalua que exista y que tenga una secuencia respuesta.
        //             console.log('Si hay una respuesta y una secuencia');

        //             // displayUserMessage(response_text);
        //             // clearInput();

        //             // await getResponse(response_id);

        //             // if (arrayResponse.next_response !== null) {

        //             //     do {  //Hacer mientras (Llama las respuestas mientras exista una proxima pregunta).
        //             //         await getResponse(arrayResponse.id);
        //             //         next_response = arrayResponse.next_response;
        //             //         console.log(" next_response ", next_response);
        //             //     } while (next_response === 1);
        //             // } else {
        //             //     console.log("Ha habido un error aca: Si hay una respuesta y una secuencia");
        //             // }

        //             // if (arrayResponse.next_question && arrayResponse.next_question !== null) {
        //             //     await getQuestion(arrayResponse.next_question);
        //             // }

        //         } else {
        //             console.log('No hay proxima pregunta pero Si hay una respuesta y no secuencia');

        //         }

        //     }
        // }


        // if (getIndex(opcion, arrayResponses)?.type_response === 2) {
        //     if (next_question && next_question !== null) {  //Si no es null llamar a la siguiente pregunta.
        //         console.log('Hay next question');
        //         displayUserMessage(response_text); //Muestra la opcion selecciona del usuario.
        //         clearInput();
        //         await getQuestion(next_question); // Obtener el nuevo bloque de pregunta y respuestas.

        //     } else if (next_question === null) { //Si es null llamar a la respuesta. //Imprimir documento

        //         if (next_response === 1) {  //Si hay siguiente respuesta
        //             console.log('No hay next question pero Hay proxima respuesta');

        //             displayUserMessage(response_text);
        //             clearInput();

        //             // Mostrar Secuencia de la respuesta
        //             do {  //Hacer mientras (Llama las respuestas mientras exista una proxima pregunta).
        //                 await getResponse(response_id);
        //                 next_response = arrayResponse.next_response;
        //             } while (next_response === 1);

        //             console.log('type_document.currentValue: ', type_document.currentValue, "type_doc: ", type_doc);
        //             if (type_document.currentValue > 0) {
        //                 const response = await createPDF(type_document.currentValue, response_id);
        //                 fillDocument(response);
        //             }

        //             // const response = await createPDF(type_doc, response_id);

        //             // fillDocument(response);



        //             if (arrayDocument.status && arrayResponse.next_question !== null) {
        //                 console.log("Proxima pregunta: ", arrayResponse.next_question);
        //                 await getQuestion(arrayResponse.next_question);
        //             } else {
        //                 displayBotMessage("No se pudo generar tu documento");
        //                 await getQuestion(endQuestion); //Quitar............................ para mientras
        //             }

        //         } else if (next_response === 0) { // Si no hay respuesta siguiente
        //             console.log('No hay next question  y No hay proxima respuesta');
        //             // displayUserMessage(response_text);
        //             console.log('arrayDocument', arrayDocument);
        //             if (response_text === 'Descargar') {
        //                 // await getResponse(null);
        //                 displayUserMessage(response_text);
        //                 clearInput();
        //                 // await downloadPDF(arrayDocument.id);
        //                 const response = await downloadPDF(arrayDocument.id); // Imprime el nombre del documento?

        //                 // console.log('type_document.currentValue: ', type_document.currentValue, "type_doc: ", type_doc);
        //                 // if (dictionaryTypesDocuments.has(type_document.currentValue)) {
        //                 //     displayBotDownloadLink(url, dictionaryTypesDocuments.get(type_document.currentValue));
        //                 // }
        //                 displayBotDownloadLink(response.url, response.filename)

        //                 await getQuestion(endQuestion);
        //             } else {
        //                 displayUserMessage(response_text);
        //                 clearInput();
        //                 console.log('Mandar por correo');
        //                 if (type_document.currentValue > 0) {
        //                     const response = await sendEmail(arrayDocument.id); //Este es el id de la respuesta como tal..
        //                     displayBotMessage(response.message);
        //                     await getQuestion(endQuestion);
        //                 }
        //                 return;
        //             }
        //         }
        //     }
        // }

    }




    sendButton.addEventListener("click", function () {

        const opcion = userInput.value.trim(); //Numero ingresado por el usuario

        if (checkIndex(opcion, arrayResponses)) { //Chequea si existe el indice en el array segun la opcion que selecciono el usuario.

            handleUserSelection(opcion); // Llama al manejador que tiene la logica de como interactua el usuario con las preguntas.

        } else { //Si no existe manda un mensaje e imprime nuevamente las opciones disponibles.
            displayUserMessage(`Opcion ${opcion}`); // Imprime la seleccion del usuario
            displayBotMessage(`Selecciona una de las opciones en la lista`); //Mensaje de alerta sobre seleccionar la opcion correcta.
            clearInput(); // Limpia el input de lo ingreso el usuario.

            displayResponses(arrayResponses.filter(option => option.questions_id === arrayQuestion.question.id));
        }

    });





    /******************************************************  Mostrar la informacion en el chat *********************************** */


    function displayBotMessage(message) {

        const botMessage = document.createElement("div");
        botMessage.className = "bot-message d-flex flex-column align-items-start";

        const timeNode = document.createElement("span");
        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        timeNode.textContent = currentTime;
        timeNode.classList.add("text-muted", "small", "mb-1");
        const messageContainer = document.createElement("div");
        messageContainer.className = "d-flex align-items-center";

        const icon = document.createElement("i");
        icon.classList.add("bi", "bi-robot", "me-2", "align-self-start");

        const textNode = document.createElement("span");
        textNode.textContent = message;
        messageContainer.appendChild(icon);
        messageContainer.appendChild(textNode);

        botMessage.appendChild(timeNode);
        botMessage.appendChild(messageContainer);
        chatContainer.appendChild(botMessage);
        // botMessage.scrollIntoView({ behavior: 'smooth' });
        // chatContainer.scrollTop = chatContainer.scrollHeight;
        setTimeout(() => {
            // chatContainer.scrollTop = chatContainer.scrollHeight;
            botMessage.scrollIntoView({ behavior: 'smooth' });
        }, 0);
    }


    function displayResponses(responses) {

        //console.log('displayResponses => ', arrayResponses.has_next_response);

        if (Array.isArray(responses)) {
            let increment = 1;
            responses.forEach(response => {
                if (response.status === 0) {
                    // let optionText = '';
                    if (arrayQuestion) {
                        const optionText = (isMessageResponse.flag) ? `${response.response_text}` : `${increment} - ${response.response_text}`;
                        const botMessage = document.createElement("div");
                        botMessage.textContent = optionText;
                        botMessage.className = "bot-message";
                        chatContainer.appendChild(botMessage);
                        increment++;
                        setTimeout(() => { botMessage.scrollIntoView({ behavior: 'smooth' }); }, 0);
                    }
                }
                //  else {
                //     const botMessage = document.createElement("div");
                //     botMessage.textContent = "No se pudo mostrar una respuesta a tu seleccion";
                //     botMessage.className = "bot-message";
                //     chatContainer.appendChild(botMessage);
                //     setTimeout(() => { botMessage.scrollIntoView({ behavior: 'smooth' }); }, 0);

                // }
            });
        }
    }


    function displayUserMessage(message) {
        const userMessage = document.createElement("div");
        userMessage.className = "user-message d-flex flex-column align-items-end";  // Cambié 'd-flex' por 'flex-column' para apilar los elementos

        const timeNode = document.createElement("span");
        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        timeNode.textContent = currentTime;
        timeNode.classList.add("text-muted", "small");

        const messageContainer = document.createElement("div");
        messageContainer.className = "d-flex align-items-center";  // Para alinear el icono junto al mensaje

        const textNode = document.createElement("span");
        textNode.textContent = message;

        const icon = document.createElement("i");
        icon.classList.add("bi", "bi-person-circle", "ms-2");

        messageContainer.appendChild(textNode);
        messageContainer.appendChild(icon);

        userMessage.appendChild(timeNode);
        userMessage.appendChild(messageContainer);

        chatContainer.appendChild(userMessage);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function displayBotDownloadLink(url, filename = "document.pdf") {
        const botMessage = document.createElement("div");
        botMessage.className = "bot-message d-flex flex-column align-items-start";

        // Agregar la hora
        const timeNode = document.createElement("span");
        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        timeNode.textContent = currentTime;
        timeNode.classList.add("text-muted", "small", "mb-1");

        // Contenedor del mensaje
        const messageContainer = document.createElement("div");
        messageContainer.className = "d-flex align-items-center";

        // Ícono del bot
        const icon = document.createElement("i");
        icon.classList.add("bi", "bi-robot", "me-2");

        // Crear enlace de descarga
        const link = document.createElement("a");
        link.href = url;
        link.download = filename;
        link.textContent = `Descargar ${filename}`;
        link.classList.add("btn", "btn-link"); // Agregar estilos Bootstrap

        // Ensamblar el mensaje
        messageContainer.appendChild(icon);
        messageContainer.appendChild(link);
        botMessage.appendChild(timeNode);
        botMessage.appendChild(messageContainer);

        // Agregar al contenedor del chat
        chatContainer.appendChild(botMessage);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function isNumber(value) {
        return Number.isInteger(value);
    }

    //Chequear si existe el index en el arreglo
    function checkIndex(input, array) {
        const index = parseInt(input - 1, 10);
        return !isNaN(index) && index >= 0 && index < array.length;
    }

    function getIndex(input, array) {
        return array[input - 1];
    }


    function clearInput() {
        document.querySelector('#userInput').value = '';
    }



})();