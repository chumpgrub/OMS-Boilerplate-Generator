export function screenIsLoading(state = false, action) {
    switch(action.type) {
        case 'SCREEN_IS_LOADING':
            return action.isLoading;
        default:
            return state;
    }
}

export function setUserName(state = '', action) {
    switch(action.type) {
        case 'SET_USER_NAME':
            return action.userName;
        default:
            return state;
    }
}
