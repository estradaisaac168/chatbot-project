(function (global) {
    global.AppConfig = {
        baseUrl: "https://3000-idx-chatbot-project-1740147096069.cluster-hf4yr35cmnbd4vhbxvfvc6cp5q.cloudworkstations.dev/"
    };

    global.buildUrl = function (path, params = {})  {
        const url = new URL(path, AppConfig.baseUrl);

        if (params && Object.keys(params).length > 0) {
            Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));
        };

        return url.toString();
    }
})(window);