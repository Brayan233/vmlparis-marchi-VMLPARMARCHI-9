var engine = {"lang":{"utility":{},"type":{}},"react":{},"web":{"utility":{},"component":{}},"fileManager":{"component":{"client":{"observer":{}}},"value":{},"entity":{"factory":{}},"template":{"icon":{},"control":{"uploadProgress":{}},"fileList":{"breadcrumbs":{},"item":{}}},"view":{"icon":{},"control":{"uploadProgress":{}},"fileList":{"breadcrumbs":{},"item":{},"trigger":{},"observer":{}}},"model":{},"controller":{"fileList":{"observer":{},"action":{}}},"setting":{}},"gui":{"utility":{},"component":{},"trigger":{}}};
{
let self = engine.lang.utility.Object = class {

    static forEach(object, callback) {
        for (let property in object) {
            if (object.hasOwnProperty(property)) {
                callback(property, object[property]);
            }
        }
    }

    static merge(result, ...objects) {
        objects.forEach(object => {
            self.forEach(object, (property, value) => {
                result[property] = value;
            });
        });

        return result;
    }
}
}
{
let self = engine.lang.utility.Type = class {

    static isString(value) {
        return typeof value === 'string';
    }

    static isNumber(value) {
        return typeof value === 'number';
    }

    static isObject(value, strict = true) {
        return typeof value === 'object' && (!strict || !self.isArray(value));
    }

    static isFunction(value) {
        return typeof value === 'function';
    }

    static isArray(value) {
        return value instanceof Array;
    }

    static arrayToObject(value, recursively) {
        let result = {};

        for (let i = 0; i < value.length; ++i) {
            result[i] = recursively && self.isArray(value[i]) ?
                self.arrayToObject(value[i], recursively) :
                value[i];
        }

        return result;
    }
}
}
{
let
    Obj = engine.lang.utility.Object,
    Type = engine.lang.utility.Type;

engine.lang.type.Object = class {

    constructor(properties = {}, context) {
        this._constructor(properties, context);
    }

    _constructor(properties, context) {
        this.set(Obj.merge(this.defaults || {}, properties), context);
        this.use(...this.traits || []);
        this.initialize && this.initialize();
    }

    set(property, value, context) {
        if (Type.isObject(property)) {
            context = value;
            Obj.forEach(property, (name, value) => {
                this.set(name, value, context);
            });
        } else if (Type.isFunction(value) && context) {
            this[property] = (...args) => {
                return value.call(context, ...args);
            };
        } else {
            this[property] = value;
        }

        return this;
    }

    use(...traits) {
        traits.forEach(trait => {
            //todo: check if trait have used already
            if (!Type.isObject(trait)) {
                trait = new trait({owner: this});
            }
            trait.properties && this.set(trait.properties, trait);
        });

        return this;
    }
}
}
{
let self = engine.lang.utility.String = class {

    static capitalize(string) {
        return self.upperCaseFirst(string);
    }

    static format(string, ...args) {
        return string.replace(/{(\d+)}/g, (match, number) => {
            return typeof args[number] !== 'undefined' ? args[number] : match;
        });
    }

    static contains(string, substring, caseSensitive = true) {
        return caseSensitive ?
            string.indexOf(substring) >= 0 :
            string.toLocaleLowerCase().indexOf(substring.toLocaleLowerCase()) >= 0;
    }

    static lowerCaseFirst(string) {
        return string.charAt(0).toLocaleLowerCase() + string.slice(1);
    }

    static upperCaseFirst(string) {
        return string.charAt(0).toLocaleUpperCase() + string.slice(1);
    }
}
}
{
let
    Str = engine.lang.utility.String;

engine.react.Observable = class {

    constructor(...observers) {
        this.observers = [];
        this.addObservers(...observers);
    }

    addObserver(observer, callback) {
        if (callback) {
            let onEvent = 'on' + Str.capitalize(observer);
            observer = {};
            observer[onEvent] = callback;
        }
        this.observers.push(observer);

        return this;
    }

    removeObserver(observer) {
        this.observers = this.observers.filter(element => {
            return element !== observer;
        });

        return this;
    }

    addObservers(...observers) {
        observers.forEach(observer => {
            this.addObserver(observer);
        });

        return this;
    }

    trigger(eventName, ...args) {
        let onEvent = 'on' + Str.capitalize(eventName);
        try {
            this.observers.forEach(observer => {
                observer[onEvent] && observer[onEvent](...args);
            });
            this.observers.forEach(observer => {
                observer[onEvent + 'Complete'] && observer[onEvent + 'Complete'](...args);
            });
        } catch (throwable) {
            this.observers.forEach(observer => {
                // todo: remove temporary solution;
                console.error(throwable);
                observer['onError'] && observer['onError'](throwable, ...args);
            });
        }
    }
}
}
{
let
    Object = engine.lang.type.Object,
    Obj = engine.lang.utility.Object,
    Observable = engine.react.Observable,
    Str = engine.lang.utility.String,
    Type = engine.lang.utility.Type;

let self = engine.react.Component = class extends Object {

    _constructor(...args) {
        super._constructor(...args);
        this.mapEvents(this.events || {});
        this.on(this);
    }

    get eventResource() {
        if (!this._observable) {
            this._observable = new Observable();
        }

        return this._observable;
    }

    on(...args) {
        this.eventResource.addObserver(...args);

        return this;
    }

    trigger(eventName, event, context) {
        this.eventResource.trigger(eventName, event, context || this);
    }

    mapEvent(property, fromEventName, toEventName) {
        if (property instanceof self) {
            property.on(fromEventName, event => {
                this.trigger(toEventName, event, this);
            });
        } else {
            property.addEventListener(fromEventName, event => {
                this.trigger(toEventName, event, this);
            });
        }
    }

    mapEvents(events) {
        Obj.forEach(events, (toEventName, properties) => {
            Obj.forEach(properties, (property, fromEventName) => {
                if (Type.isArray(this[property])) {
                    this[property].forEach(item => {
                        this.mapEvent(item, fromEventName, toEventName);
                    });
                } else {
                    this.mapEvent(this[property], fromEventName, toEventName);
                }
            });
        });
    }

    set(property, value, context) {
        if (property.length > 2 && property.substr(0, 2) === 'on' &&
            property.charAt(2) === property.charAt(2).toUpperCase()
        ) {
            let eventName = Str.lowerCaseFirst(property.substr(2));

            return this.on(eventName, value);
        }

        return super.set(property, value, context);
    }
}
}
{
let self = engine.web.utility.Cookie = class {

    static enabled() {
        return navigator.cookieEnabled;
    }

    static set(name, value, options = {}) {
        if (!self.enabled()) {
            return;
        }

        if (options.expires) {
            options.expires = options.expires.toUTCString();
        }

        let cookie = name + '=' + encodeURIComponent(value);
        for (let key in options) {
            cookie += ';' + (key !== 'secure' ? key + '=' + options[key] : key);
        }

        document.cookie = cookie;
    }

    static get(name, byDefault = null) {
        if (!self.enabled()) {
            return byDefault;
        }

        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));

        return matches ? decodeURIComponent(matches[1]) : byDefault;
    }

    static remove(name) {
        if (!self.enabled()) {
            return;
        }

        self.set(name, '', {
            expires: new Date(Date.now() - 1000)
        });
    }
}
}
{
let
    Object = engine.lang.type.Object,
    Cookie = engine.web.utility.Cookie;

engine.web.component.User = class extends Object {

    read(name, byDefault = null) {
        return this.load()[name] || byDefault;
    }

    write(name, value) {
        let data = this.load();
        data[name] = value;
        this.store(data);
    }

    load() {
        if (!this.applicationId) {
            return {};
        }

        let cookie = Cookie.get(this.applicationId);

        return cookie !== null ? JSON.parse(cookie) : {};
    }

    store(data) {
        Cookie.set(this.applicationId, JSON.stringify(data), {
            expires: new Date(Date.now() + this.expires)
        });
    }
}
}
{
let
    Component = engine.react.Component,
    Observable = engine.react.Observable,
    Obj = engine.lang.utility.Object;

engine.web.component.Client = class extends Component {

    exchange(request, ...observers) {
        let
            client = new XMLHttpRequest(),
            eventResource = this.mergeObservers(...observers);

        client.onload = client.onerror = () => {
            let event = {
                request: request,
                response: {
                    status: {
                        code: client.status,
                        text: client.statusText
                    },
                    body: client.responseText
                }
            };

            eventResource.trigger('close', event);

            switch (String(client.status).charAt(0)) {
                case '1':
                    eventResource.trigger('information', event);
                    break;
                case '2':
                    eventResource.trigger('success', event);
                    break;
                case '3':
                    eventResource.trigger('redirection', event);
                    break;
                case '4':
                    eventResource.trigger('clientError', event);
                    break;
                case '5':
                    eventResource.trigger('serverError', event);
                    break;
            }

            eventResource.trigger('statusCode' + client.status, event);
        };

        client.upload.onprogress = progress => {
            if (progress.lengthComputable) {
                request.prevProgress = request.progress || null;
                request.progress = progress;

                eventResource.trigger('progress', {
                    request: request
                });
            }
        };

        eventResource.trigger('before', {
            request: request
        });

        client.open(request.method, request.url, true);

        request.abort = () => {
            client.abort();
            eventResource.trigger('abort', {
                request: request
            });
        };

        eventResource.trigger('open', {
            request: request
        });

        request.headers && Obj.forEach(request.headers, (name, value) => {
            client.setRequestHeader(name, value);
        });

        client.send(request.body);
    }

    mergeObservers(...observers) {
        let eventResource = new Observable(...this.eventResource.observers);

        return eventResource.addObservers(...observers);
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.react.Observer = class extends Object {

    initialize() {
        this.owner.on(this);
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.component.client.observer.MakeRequestURL = class extends Observer {

    onBefore(event) {
        if (event.request.target) {
            event.request.url = this.owner.createUrl(event.request.target, event.request.queryParams);
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.component.client.observer.AddCSRFToken = class extends Observer {

    onBefore(event) {
        event.request.headers = event.request.headers || {};
        event.request.headers[this.owner.csrfTokenName] = this.owner.csrfToken;
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.component.client.observer.MakeJSONRequest = class extends Observer {

    onBefore(event) {
        if (event.request.bodyParams) {
            event.request.body = JSON.stringify(event.request.bodyParams);
        }
    }
}
}
{
engine.fileManager.value.Size = class {

    constructor(value) {
        this._value = value;
    }

    get value() {
        return this._value;
    }

    toHumanString() {
        let bytes = this.value,
            thresh = 1024;

        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }

        let units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
            u = -1;
        do {
            bytes /= thresh;
            ++u;
        } while (Math.abs(bytes) >= thresh && u < units.length - 1);

        return bytes.toFixed(1) + ' ' + units[u];
    }
}
}
{
let
    Size = engine.fileManager.value.Size;

engine.fileManager.entity.File = class {

    constructor(data) {
        this._data = data;
    }

    get id() {
        return this._data.id;
    }

    get prevId() {
        return this._data.prevId;
    }

    get parentId() {
        return this._data.parentId;
    }

    get class() {
        return this._data.class;
    }

    get breadcrumbs() {
        return this._data.breadcrumbs;
    }

    get baseName() {
        return this._data.baseName;
    }

    get name() {
        return this._data.name;
    }

    set name(value) {
        this._data.name = value;
    }

    get type() {
        return this._data.type;
    }

    get extension() {
        return this._data.extension;
    }

    get permissions() {
        return this._data.permissions;
    }

    get size() {
        if (!this._size) {
            this._size = new Size(this._data.size);
        }

        return this._size;
    }

    get lastModified() {
        if (!this._lastModified) {
            this._lastModified = new Date(this._data.lastModified * 1000);
        }

        return this._lastModified;
    }

    get isTrash() {
        return '$trash' === this.name;
    }

    inBreadcrumbs(id) {
        for (let i = 0; this.breadcrumbs[i]; i++) {
            if (this.breadcrumbs[i].id === id) {
                return true;
            }
        }

        return false;
    }
}
}
{
let
    File = engine.fileManager.entity.File;

let self = engine.fileManager.entity.Hyperlink = class extends File {

    get url() {
        return this._data.url;
    }
}
}
{
let
    File = engine.fileManager.entity.File,
    Hyperlink = engine.fileManager.entity.Hyperlink;

let self = engine.fileManager.entity.Directory = class extends File {

    get pattern() {
        return this._data.pattern;
    }

    get names() {
        if (!this._names) {
            this._names = [];
            this._data.children && this._data.children.forEach(childRaw => {
                this._names.push(childRaw.name);
            });
        }

        return this._names;
    }

    get children() {
        if (!this._children) {
            this._children = [];
            this._data.children && this._data.children.forEach(childRaw => {
                switch (childRaw.class) {
                    case 'file':
                        this._children.push(new File(childRaw));
                        break;
                    case 'hyperlink':
                        this._children.push(new Hyperlink(childRaw));
                        break;
                    case 'directory':
                        this._children.push(new self(childRaw));
                        break;
                }
            });
        }

        return this._children;
    }

    hasChildren() {
        return !!this.children.length;
    }

    containsName(name) {
        return this.names.indexOf(name) > -1;
    }
}
}
{
let
    DirectoryEntity = engine.fileManager.entity.Directory;

engine.fileManager.entity.factory.Directory = class {

    createEntity(raw) {
        return new DirectoryEntity(raw);
    }

    createCollection(collectionRaw) {
        let collection = [];
        collectionRaw.forEach(raw => {
            collection.push(this.createEntity(raw));
        });

        return collection;
    }
}
}
{
let
    Observer = engine.react.Observer,
    Type = engine.lang.utility.Type,
    DirectoryFactory = engine.fileManager.entity.factory.Directory;

engine.fileManager.component.client.observer.HandleJSONResponse = class extends Observer {

    get factory() {
        if (!this._factory) {
            this._factory = new DirectoryFactory();
        }
        
        return this._factory
    }

    onSuccess(event) {
        let parsedBody = JSON.parse(event.response.body);

        if (Type.isArray(parsedBody)) {
            event.collection = this.factory.createCollection(parsedBody);
        } else {
            event.entity = this.factory.createEntity(parsedBody);
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.component.client.observer.ManageRequestProgress = class extends Observer {

    get requestProgress() {
        return this.owner.requestProgress;
    }

    onOpen() {
        this.requestProgress.show();
    }

    onProgress() {
        this.requestProgress.show();
    }

    onClose() {
        this.requestProgress.hide();
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.component.client.observer.ErrorMessenger = class extends Observer {

    get errorManager() {
        return this.owner.errorManager;
    }

    onClientError(event) {
        this.errorManager.handleError(event.response.status.text);
    }

    onServerError(event) {
        this.errorManager.handleError(event.response.status.text);
    }

    onError(throwable) {
        this.errorManager.handleError(throwable);
    }
}
}
{
let
    Type = engine.lang.utility.Type,
    Obj = engine.lang.utility.Object;

let self = engine.web.utility.Query = class {

    static stringify(object, prefix) {
        let str = [];

        Obj.forEach(object, (property, value) => {
            let key = prefix ? prefix + '[' + property + ']' : property;
            if (Type.isArray(value)) {
                value = Type.arrayToObject(value, true);
            }
            str.push(Type.isObject(value) ? self.stringify(value, key) :
                encodeURIComponent(key) + '=' + encodeURIComponent(value));
        });

        return str.join('&');
    }

    static parse(str, array) {
        let strArr = String(str).replace(/^&/, '').replace(/&$/, '').split('&'),
            sal = strArr.length,
            i, j, ct, p, lastObj, obj, undef, chr, tmp, key, value, postLeftBracketPos, keys, keysLen,
            _fixStr = function (str) {
                return decodeURIComponent(str.replace(/\+/g, '%20'))
            };

        for (i = 0; i < sal; i++) {
            tmp = strArr[i].split('=');
            key = _fixStr(tmp[0]);
            value = (tmp.length < 2) ? '' : _fixStr(tmp[1]);

            while (key.charAt(0) === ' ') {
                key = key.slice(1);
            }
            if (key.indexOf('\x00') > -1) {
                key = key.slice(0, key.indexOf('\x00'));
            }
            if (key && key.charAt(0) !== '[') {
                keys = [];
                postLeftBracketPos = 0;
                for (j = 0; j < key.length; j++) {
                    if (key.charAt(j) === '[' && !postLeftBracketPos) {
                        postLeftBracketPos = j + 1;
                    } else if (key.charAt(j) === ']') {
                        if (postLeftBracketPos) {
                            if (!keys.length) {
                                keys.push(key.slice(0, postLeftBracketPos - 1));
                            }
                            keys.push(key.substr(postLeftBracketPos, j - postLeftBracketPos));
                            postLeftBracketPos = 0;
                            if (key.charAt(j + 1) !== '[') {
                                break;
                            }
                        }
                    }
                }
                if (!keys.length) {
                    keys = [key];
                }
                for (j = 0; j < keys[0].length; j++) {
                    chr = keys[0].charAt(j);
                    if (chr === ' ' || chr === '.' || chr === '[') {
                        keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1);
                    }
                    if (chr === '[') {
                        break;
                    }
                }

                obj = array;
                for (j = 0, keysLen = keys.length; j < keysLen; j++) {
                    key = keys[j].replace(/^['"]/, '').replace(/['"]$/, '');
                    lastObj = obj;
                    if ((key !== '' && key !== ' ') || j === 0) {
                        if (obj[key] === undef) {
                            obj[key] = {};
                        }
                        obj = obj[key];
                    } else {
                        ct = -1;
                        for (p in obj) {
                            if (obj.hasOwnProperty(p)) {
                                if (+p > ct && p.match(/^\d+$/g)) {
                                    ct = +p;
                                }
                            }
                        }
                        key = ct + 1;
                    }
                }
                lastObj[key] = value;
            }
        }
    }
}
}
{
let
    WebClient = engine.web.component.Client,
    MakeRequestURL = engine.fileManager.component.client.observer.MakeRequestURL,
    AddCSRFToken = engine.fileManager.component.client.observer.AddCSRFToken,
    MakeJSONRequest = engine.fileManager.component.client.observer.MakeJSONRequest,
    HandleJSONResponse = engine.fileManager.component.client.observer.HandleJSONResponse,
    ManageRequestProgress = engine.fileManager.component.client.observer.ManageRequestProgress,
    ErrorMessenger = engine.fileManager.component.client.observer.ErrorMessenger,
    Query = engine.web.utility.Query;

engine.fileManager.component.Client = class extends WebClient {

    get traits() {
        return [
            MakeRequestURL,
            AddCSRFToken,
            MakeJSONRequest,
            HandleJSONResponse,
            ManageRequestProgress,
            ErrorMessenger
        ];
    }

    open(target, queryParams, ...args) {
        queryParams[this.csrfTokenName] = this.csrfToken;
        window.open(this.createUrl(target, queryParams), ...args);
    }

    createUrl(target, queryParams = {}) {
        let url = this.serverUrl;

        queryParams.action = target;

        url += '?' + Query.stringify(queryParams);

        return url;
    }
}
}
{
engine.gui.utility.Element = class {
    
    static clear(element) {
        while (element.hasChildNodes()) {
            element.removeChild(element.firstChild);
        }
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.gui.component.Template = class extends Object {

    initialize() {
        this.owner._element = document.createElement('div');
    }
}
}
{
let
    Component = engine.react.Component,
    Element = engine.gui.utility.Element,
    Template = engine.gui.component.Template;

let self = engine.gui.component.View = class extends Component {

    get template() {
        return Template;
    }

    get element() {
        return this._element;
    }

    _constructor(...args) {
        this.use(this.template);
        super._constructor(...args);
        this.data = {};
    }

    clear() {
        Element.clear(this.element);
    }

    appendTo(element) {
        element.appendChild(this.element);
    }
}
}
{
let self = engine.gui.utility.ClassName = class {

    static has(element, className) {
        return !!element.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'))
    }

    static add(element, className) {
        if (!self.has(element, className)) {
            element.className += element.className ? ' ' + className : className;
        }
    }

    static set(element, className) {
        element.className = className;
    }

    static remove(element, className) {
        if (self.has(element, className)) {
            element.className = element.className.replace(new RegExp('(\\s|^)' + className + '(\\s|$)'), '');
        }
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.icon.Layout = class extends Object {

    initialize() {
        let element0 = document.createElement('i');element0.setAttribute('class', 'engine-fileManager__i');this.owner._element = element0;
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.icon.Layout;

engine.fileManager.view.icon.Layout = class extends View {

    get template() {
        return Template;
    }
}
}
{
let
    Object = engine.lang.type.Object,
    ClassName = engine.gui.utility.ClassName,
    Icon = engine.fileManager.view.icon.Layout;

engine.fileManager.view.icon.Factory = class extends Object {

    create(key) {
        let icon = new Icon();
        ClassName.add(icon.element, key ? this.classByKey(key) : '');

        return icon;
    }

    classByKey(key) {
        return this.iconClasses[key] || this.defaultIconClass;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.Button = class extends Object {

    initialize() {
        let element0 = document.createElement('button');element0.setAttribute('class', 'engine-fileManager__button');this.owner._element = element0;
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.control.Button;

engine.fileManager.view.control.Button = class extends View {

    get template() {
        return Template;
    }

    get events() {
        return {
            click: {element: 'click'}
        }
    }

    set icon(value) {
        this._icon = value;
        this.render();
    }

    set caption(value) {
        this._caption = value;
        this.render();
    }

    set title(value) {
        this._title = value;
        this.render();
    }

    disabled(value) {
        this.element.disabled = value;
    }

    blur() {
        this.element.blur();
    }

    render() {
        this.clear();

        this._icon && this.element.appendChild(this._icon.element);
        this._caption && this.element.appendChild(document.createTextNode(this._caption));

        if (this._title) {
            this.element.title = this._title;
        }

        return this.element;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.UploadButton = class extends Object {

    initialize() {
        let element0 = document.createElement('span');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('input');element2.setAttribute('type', 'file');element2.setAttribute('multiple', 'multiple');element2.setAttribute('style', 'display: none;');this.owner.input = element2;element0.appendChild(element2);let element3 = document.createTextNode('');element0.appendChild(element3);let element4 = document.createElement('button');element4.setAttribute('class', 'engine-fileManager__button');this.owner.button = element4;element0.appendChild(element4);let element5 = document.createTextNode('');element0.appendChild(element5);
    }
}
}
{
let
    View = engine.gui.component.View,
    Element = engine.gui.utility.Element,
    Template = engine.fileManager.template.control.UploadButton;

engine.fileManager.view.control.UploadButton = class extends View {

    get template() {
        return Template;
    }

    get events() {
        return {
            click: {button: 'click'},
            change: {input: 'change'}
        }
    }

    set icon(value) {
        this._icon = value;
        this.render();
    }

    set caption(value) {
        this._caption = value;
        this.render();
    }

    set title(value) {
        this._title = value;
        this.render();
    }

    disabled(value) {
        this.button.disabled = value;
    }

    blur() {
        this.button.blur();
    }

    reset() {
        this.input.value = null;
    }

    render() {
        Element.clear(this.button);

        this._icon && this.button.appendChild(this._icon.element);
        this._caption && this.button.appendChild(document.createTextNode(this._caption));

        if (this._title) {
            this.element.title = this._title;
        }

        return this.element;
    }

    onClick() {
        this.blur();
        this.input.click();
    }

    onChange(event) {
        let files = Array.prototype.slice.call(event.target.files || event.dataTransfer.files);
        this.trigger('selectFiles', {
            files: files
        });
        this.reset();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.Logo = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__logo');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('img');element2.setAttribute('src', './styles/logo.png');element0.appendChild(element2);let element3 = document.createTextNode('');element0.appendChild(element3);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.Logo;

engine.fileManager.view.Logo = class extends View {

    get traits() {
        return [
            Template
        ];
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.Toolbar = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__toolbar');this.owner._element = element0;
    }
}
}
{
let
    View = engine.gui.component.View,
    Button = engine.fileManager.view.control.Button,
    UploadButton = engine.fileManager.view.control.UploadButton,
    Logo = engine.fileManager.view.Logo,
    Template = engine.fileManager.template.Toolbar;

engine.fileManager.view.Toolbar = class extends View {

    get template() {
        return Template;
    }

    initialize() {


        this.logo = new Logo();

        this.open = new Button({
            icon: this.icon.create('folderOpen'),
            caption: 'Open',
            title: this.hotKeys.openKey
        });

        this.rename = new Button({
            icon: this.icon.create('edit'),
            caption: 'Rename',
            title: this.hotKeys.renameKey
        });

        this.permissions = new Button({
            icon: this.icon.create('permissions'),
            caption: 'Permissions',
            title: this.hotKeys.permissionsKey
        });

        this.copy = new Button({
            icon: this.icon.create('copy'),
            caption: 'Copy',
            title: this.hotKeys.copyKey
        });

        this.move = new Button({
            icon: this.icon.create('move'),
            caption: 'Move',
            title: this.hotKeys.moveKey
        });

        this.trash = new Button({
            icon: this.icon.create('trash'),
            caption: 'Trash',
            title: this.hotKeys.trashKey
        });

        this.remove = new Button({
            icon: this.icon.create('remove'),
            caption: 'Remove',
            title: this.hotKeys.removeKey
        });

        this.createDirectory = new Button({
            icon: this.icon.create('dir'),
            caption: 'Create Folder',
            title: this.hotKeys.createFolderKey
        });

        this.createHyperlink = new Button({
            icon: this.icon.create('hyperlink'),
            caption: 'Create Hyperlink',
            title: this.hotKeys.createHyperlinkKey
        });

        this.upload = new UploadButton({
            icon: this.icon.create('upload'),
            caption: 'Upload',
            title: this.hotKeys.uploadKey
        });

        this.download = new Button({
            icon: this.icon.create('download'),
            caption: 'Download',
            title: this.hotKeys.downloadKey
        });

        this.search = new Button({
            icon: this.icon.create('search'),
            caption: 'Search',
            title: this.hotKeys.searchKey
        });

        this.openTrash = new Button({
            icon: this.icon.create('trash'),
            caption: 'Open Trash'
        });
    }

    get events() {
        return {
            open: {open: 'click'},
            rename: {rename: 'click'},
            permissions: {permissions: 'click'},
            copy: {copy: 'click'},
            move: {move: 'click'},
            trash: {trash: 'click'},
            remove: {remove: 'click'},
            createDirectory: {createDirectory: 'click'},
            createHyperlink: {createHyperlink: 'click'},
            upload: {upload: 'selectFiles'},
            download: {download: 'click'},
            search: {search: 'click'},
            openTrash: {openTrash: 'click'}
        }
    }

    render() {
        this.clear();

        this.element.appendChild(this.logo.element);
        this.element.appendChild(this.open.element);
        this.element.appendChild(this.rename.element);
        this.element.appendChild(this.permissions.element);
        this.element.appendChild(this.copy.element);
        this.element.appendChild(this.move.element);
        this.element.appendChild(this.trash.element);
        this.element.appendChild(this.remove.element);
        this.element.appendChild(this.createDirectory.element);
        this.element.appendChild(this.createHyperlink.element);
        this.element.appendChild(this.upload.element);
        this.element.appendChild(this.download.element);
        this.element.appendChild(this.search.element);
        this.element.appendChild(this.openTrash.element);

        return this.element;
    }
}
}
{
let
    Object = engine.lang.type.Object;

let self = engine.gui.trigger.Activity = class extends Object {

    static get instances() {
        if (!self._instances) {
            self._instances = [];
        }

        return self._instances;
    }

    get properties() {
        return {
            activate: this.activate,
            deactivate: this.deactivate
        };
    }

    initialize() {
        self.instances.push(this.owner);

        this.owner.element.addEventListener('click', () => {
            this.owner.activate();
        }, true);

        document.addEventListener('click', () => {
            this.owner.deactivate();
        }, true);
    }

    activate() {
        if (this.owner.isActive) {
            return;
        }
        this.owner.isActive = true;
        this.owner.trigger('activate');

        self.instances.forEach(instance => {
            instance !== this.owner && instance.deactivate();
        });
    }

    deactivate() {
        this.owner.isActive = false;
        this.owner.trigger('deactivate');
    }
}
}
{
engine.lang.utility.Array = class {

    static contains(list, value) {
        return list.indexOf(value) > -1;
    }
}
}
{
let
    Object = engine.lang.type.Object,
    Arr = engine.lang.utility.Array,
    Type = engine.lang.utility.Type,
    Str = engine.lang.utility.String;

let self = engine.gui.component.HotKey = class extends Object {

    static isSpecialKey(key) {
        return Arr.contains([
            'alt',
            'ctrl',
            'shift',
            'meta',
            'access'
        ], key.toLocaleLowerCase());
    }

    static codeFromKey(key) {
        if (parseInt(key) == key) {
            return parseInt(key);
        }

        switch (key.toLocaleLowerCase()) {
            case 'enter':
                return 13;
            case 'esc':
                return 27;
            case 'delete':
                return 46;
        }

        return key.toLocaleUpperCase().charCodeAt(0);
    }

    isRecognized(keyboardEvent) {
        for (let i = 0; this.keys[i]; i++) {
            let key = this.keys[i];

            if (self.isSpecialKey(key)) {
                if (!keyboardEvent[key.toLocaleLowerCase() + 'Key']) {
                    return false;
                }
                continue;
            }

            if (Str.contains(key, '-')) {
                let exceptKeys = key.split('-');
                key = exceptKeys.shift();
                for (let j = 0; exceptKeys[j]; j++) {
                    let exceptKey = exceptKeys[j];
                    if (!self.isSpecialKey(exceptKey) || keyboardEvent[exceptKey.toLocaleLowerCase() + 'Key']) {
                        return false;
                    }
                }
            }

            if (keyboardEvent.keyCode !== self.codeFromKey(key)) {
                return false;
            }
        }

        return true;
    }

    set keys(value) {
        if (Type.isNumber) {
            value = value.toString();
        }

        this._keys = value.split('+');
    }

    get keys() {
        return this._keys;
    }
}
}
{
let
    Obj = engine.lang.utility.Object,
    HotKey = engine.gui.component.HotKey,
    Observer = engine.react.Observer;

engine.gui.trigger.HotKeys = class extends Observer {

    initialize() {
        document.addEventListener('keydown', event => {
            if (this.owner.isActive) {
                this.hotKeys.forEach(hotKey => {
                    if (hotKey.isRecognized(event)) {
                        event.preventDefault();
                        this.owner.trigger(hotKey.name, event);
                    }
                });
            }
        });
    }

    get hotKeys() {
        if (!this._hotKeys) {
            this._hotKeys = [];
            Obj.forEach(this.owner.hotKeys, (name, value) => {
                this._hotKeys.push(new HotKey({
                    name: name,
                    keys: value
                }));
            });
        }

        return this._hotKeys;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.breadcrumbs.Root = class extends Object {

    initialize() {
        let element0 = document.createElement('span');element0.setAttribute('class', 'item');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('i');element2.setAttribute('class', 'engine-fileManager__i icon-home');element0.appendChild(element2);let element3 = document.createTextNode('');element0.appendChild(element3);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.breadcrumbs.Root;

engine.fileManager.view.fileList.breadcrumbs.Root = class extends View {

    get template() {
        return Template;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.breadcrumbs.Item = class extends Object {

    initialize() {
        let element0 = document.createElement('span');element0.setAttribute('class', 'item');this.owner._element = element0;let element1 = document.createTextNode('');this.owner._caption = element1;element0.appendChild(element1);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.breadcrumbs.Item;

engine.fileManager.view.fileList.breadcrumbs.Item = class extends View {

    get template() {
        return Template;
    }

    set caption(value) {
        this._caption.nodeValue = value;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.breadcrumbs.Search = class extends Object {

    initialize() {
        let element0 = document.createElement('span');element0.setAttribute('class', 'item');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('i');element2.setAttribute('class', 'engine-fileManager__i icon-search');element0.appendChild(element2);let element3 = document.createTextNode('');this.owner._caption = element3;element0.appendChild(element3);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.breadcrumbs.Search;

engine.fileManager.view.fileList.breadcrumbs.Search = class extends View {

    get template() {
        return Template;
    }

    set caption(value) {
        this._caption.nodeValue = value;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.breadcrumbs.Separator = class extends Object {

    initialize() {
        let element0 = document.createElement('span');element0.setAttribute('class', 'engine-fileManager__list-breadcrumbs-separator');this.owner._element = element0;let element1 = document.createTextNode('/');element0.appendChild(element1);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.breadcrumbs.Separator;

engine.fileManager.view.fileList.breadcrumbs.Separator = class extends View {

    get template() {
        return Template;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.breadcrumbs.Layout = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__list-breadcrumbs');this.owner._element = element0;
    }
}
}
{
let
    View = engine.gui.component.View,
    Root = engine.fileManager.view.fileList.breadcrumbs.Root,
    Item = engine.fileManager.view.fileList.breadcrumbs.Item,
    Search = engine.fileManager.view.fileList.breadcrumbs.Search,
    Separator = engine.fileManager.view.fileList.breadcrumbs.Separator,
    Template = engine.fileManager.template.fileList.breadcrumbs.Layout;

engine.fileManager.view.fileList.breadcrumbs.Layout = class extends View {

    get template() {
        return Template;
    }

    render() {
        this.clear();
        this.items = [];

        this.directory.breadcrumbs.forEach((pathItem, index) => {
            let item = null;

            if (0 === index) {
                item = new Root({
                    id: pathItem.id
                });
            } else {
                this.element.appendChild((new Separator()).element);
                item = new Item({
                    id: pathItem.id,
                    caption: pathItem.name
                });
            }

            this.element.appendChild(item.element);
            this.items.push(item);
        });

        if (this.directory.pattern) {
            this.element.appendChild((new Separator()).element);
            this.element.appendChild((new Search({caption: this.directory.pattern})).element);
        }

        return this.element;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.UploadProgress = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__upload-progress');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__progress total');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');element4.setAttribute('class', 'engine-fileManager__progress-bar');let element5 = document.createTextNode('');element4.appendChild(element5);let element6 = document.createElement('div');element6.setAttribute('class', 'engine-fileManager__progress-bar-line');this.owner.line = element6;element6.setAttribute('style', 'width: 0');element4.appendChild(element6);let element7 = document.createTextNode('');element4.appendChild(element7);let element8 = document.createElement('div');element8.setAttribute('class', 'engine-fileManager__progress-percent');let element9 = document.createTextNode('');this.owner.percent = element9;element8.appendChild(element9);element4.appendChild(element8);let element10 = document.createTextNode('');element4.appendChild(element10);element2.appendChild(element4);let element11 = document.createTextNode('');element2.appendChild(element11);let element12 = document.createElement('div');element12.setAttribute('class', 'engine-fileManager__progress-title');let element13 = document.createTextNode('');this.owner._title = element13;element12.appendChild(element13);element2.appendChild(element12);let element14 = document.createTextNode('');element2.appendChild(element14);element0.appendChild(element2);let element15 = document.createTextNode('');element0.appendChild(element15);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.UploadProgress;

engine.fileManager.view.fileList.UploadProgress = class extends View {

    get template() {
        return Template;
    }

    get events() {
        return {
            showDetails: {element: 'click'}
        };
    }

    set title(value) {
        this._title.nodeValue = value;
    }

    update(percent) {
        this.value = percent;
        this.line.style.width = percent + '%';
        this.percent.nodeValue = percent > 0 ? percent + '%' : '';
        this.element.style.cursor = percent ? 'pointer' : 'default';
    }

    onShowDetails() {
        this.value && this.uploadProgressDialog.show();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.Header = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__list-header');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('table');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('tr');let element5 = document.createTextNode('');element4.appendChild(element5);let element6 = document.createElement('td');let element7 = document.createTextNode('');element6.appendChild(element7);let element8 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element8);let element9 = document.createTextNode('');element8.appendChild(element9);let element10 = document.createElement('input');element10.setAttribute('type', 'checkbox');this.owner.checkbox = element10;element8.appendChild(element10);let element11 = document.createTextNode('');element8.appendChild(element11);element6.appendChild(element8);let element12 = document.createTextNode('');element6.appendChild(element12);element4.appendChild(element6);let element13 = document.createTextNode('');element4.appendChild(element13);let element14 = document.createElement('td');this.owner.columns = this.owner.columns || {};this.owner.columns.name = element14;let element15 = document.createTextNode('');element14.appendChild(element15);let element16 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element16);let element17 = document.createTextNode('Name');element16.appendChild(element17);let element18 = document.createElement('i');this.owner.sortIcons = this.owner.sortIcons || {};this.owner.sortIcons.name = element18;element16.appendChild(element18);let element19 = document.createTextNode('');element16.appendChild(element19);element14.appendChild(element16);let element20 = document.createTextNode('');element14.appendChild(element20);element4.appendChild(element14);let element21 = document.createTextNode('');element4.appendChild(element21);let element22 = document.createElement('td');this.owner.columns = this.owner.columns || {};this.owner.columns.extension = element22;let element23 = document.createTextNode('');element22.appendChild(element23);let element24 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element24);let element25 = document.createTextNode('Type');element24.appendChild(element25);let element26 = document.createElement('i');this.owner.sortIcons = this.owner.sortIcons || {};this.owner.sortIcons.extension = element26;element24.appendChild(element26);let element27 = document.createTextNode('');element24.appendChild(element27);element22.appendChild(element24);let element28 = document.createTextNode('');element22.appendChild(element28);element4.appendChild(element22);let element29 = document.createTextNode('');element4.appendChild(element29);let element30 = document.createElement('td');this.owner.columns = this.owner.columns || {};this.owner.columns.size = element30;let element31 = document.createTextNode('');element30.appendChild(element31);let element32 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element32);let element33 = document.createTextNode('Size');element32.appendChild(element33);let element34 = document.createElement('i');this.owner.sortIcons = this.owner.sortIcons || {};this.owner.sortIcons.size = element34;element32.appendChild(element34);let element35 = document.createTextNode('');element32.appendChild(element35);element30.appendChild(element32);let element36 = document.createTextNode('');element30.appendChild(element36);element4.appendChild(element30);let element37 = document.createTextNode('');element4.appendChild(element37);let element38 = document.createElement('td');this.owner.columns = this.owner.columns || {};this.owner.columns.permissions = element38;let element39 = document.createTextNode('');element38.appendChild(element39);let element40 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element40);let element41 = document.createTextNode('Perm');element40.appendChild(element41);let element42 = document.createElement('i');this.owner.sortIcons = this.owner.sortIcons || {};this.owner.sortIcons.permissions = element42;element40.appendChild(element42);let element43 = document.createTextNode('');element40.appendChild(element43);element38.appendChild(element40);let element44 = document.createTextNode('');element38.appendChild(element44);element4.appendChild(element38);let element45 = document.createTextNode('');element4.appendChild(element45);let element46 = document.createElement('td');this.owner.columns = this.owner.columns || {};this.owner.columns.lastModified = element46;let element47 = document.createTextNode('');element46.appendChild(element47);let element48 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element48);let element49 = document.createTextNode('Modified');element48.appendChild(element49);let element50 = document.createElement('i');this.owner.sortIcons = this.owner.sortIcons || {};this.owner.sortIcons.lastModified = element50;element48.appendChild(element50);let element51 = document.createTextNode('');element48.appendChild(element51);element46.appendChild(element48);let element52 = document.createTextNode('');element46.appendChild(element52);element4.appendChild(element46);let element53 = document.createTextNode('');element4.appendChild(element53);element2.appendChild(element4);let element54 = document.createTextNode('');element2.appendChild(element54);element0.appendChild(element2);let element55 = document.createTextNode('');element0.appendChild(element55);
    }
}
}
{
let
    View = engine.gui.component.View,
    ClassName = engine.gui.utility.ClassName,
    Template = engine.fileManager.template.fileList.Header;

engine.fileManager.view.fileList.Header = class extends View {

    get template() {
        return Template;
    }

    sortBy(field, desc) {
        ClassName.set(this.sortIcons[field], this.icon.classByKey(desc ? 'sortDesc' : 'sortAsc'));
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.Body = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__list-body');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('table');this.owner.table = element2;element0.appendChild(element2);let element3 = document.createTextNode('');element0.appendChild(element3);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.Body;

engine.fileManager.view.fileList.Body = class extends View {

    get template() {
        return Template;
    }

    render() {
        this.clear();
        this.element.appendChild(this.table);
        this.items.forEach(item => {
            this.table.appendChild(item.element);
        });

        return this.element;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.item.Parent = class extends Object {

    initialize() {
        let element0 = document.createElement('tr');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('td');element2.setAttribute('colspan', '6');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');let element5 = document.createTextNode('..');element4.appendChild(element5);element2.appendChild(element4);let element6 = document.createTextNode('');element2.appendChild(element6);element0.appendChild(element2);let element7 = document.createTextNode('');element0.appendChild(element7);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.item.Parent;

engine.fileManager.view.fileList.item.Parent = class extends View {

    get template() {
        return Template;
    }
};
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.item.File = class extends Object {

    initialize() {
        let element0 = document.createElement('tr');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('td');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element4);let element5 = document.createElement('input');element5.setAttribute('type', 'checkbox');this.owner.checkbox = element5;element4.appendChild(element5);element2.appendChild(element4);let element6 = document.createTextNode('');element2.appendChild(element6);element0.appendChild(element2);let element7 = document.createTextNode('');element0.appendChild(element7);let element8 = document.createElement('td');let element9 = document.createTextNode('');element8.appendChild(element9);let element10 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element10);let element11 = document.createTextNode('');this.owner.name = element11;element10.appendChild(element11);element8.appendChild(element10);let element12 = document.createTextNode('');element8.appendChild(element12);element0.appendChild(element8);let element13 = document.createTextNode('');element0.appendChild(element13);let element14 = document.createElement('td');let element15 = document.createTextNode('');element14.appendChild(element15);let element16 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element16);let element17 = document.createTextNode('');this.owner.extension = element17;element16.appendChild(element17);element14.appendChild(element16);let element18 = document.createTextNode('');element14.appendChild(element18);element0.appendChild(element14);let element19 = document.createTextNode('');element0.appendChild(element19);let element20 = document.createElement('td');let element21 = document.createTextNode('');element20.appendChild(element21);let element22 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element22);let element23 = document.createTextNode('');this.owner.size = element23;element22.appendChild(element23);element20.appendChild(element22);let element24 = document.createTextNode('');element20.appendChild(element24);element0.appendChild(element20);let element25 = document.createTextNode('');element0.appendChild(element25);let element26 = document.createElement('td');let element27 = document.createTextNode('');element26.appendChild(element27);let element28 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element28);let element29 = document.createTextNode('');this.owner.permissions = element29;element28.appendChild(element29);element26.appendChild(element28);let element30 = document.createTextNode('');element26.appendChild(element30);element0.appendChild(element26);let element31 = document.createTextNode('');element0.appendChild(element31);let element32 = document.createElement('td');let element33 = document.createTextNode('');element32.appendChild(element33);let element34 = document.createElement('div');this.owner.contents = this.owner.contents || [];this.owner.contents.push(element34);let element35 = document.createTextNode('');this.owner.modified = element35;element34.appendChild(element35);element32.appendChild(element34);let element36 = document.createTextNode('');element32.appendChild(element36);element0.appendChild(element32);let element37 = document.createTextNode('');element0.appendChild(element37);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.fileList.item.File;

engine.fileManager.view.fileList.item.File = class extends View {

    get template() {
        return Template;
    }

    initialize() {
        // icon
        this.contents[1].insertBefore(this.icon.element, this.contents[1].firstChild);

        // fields
        this.name.nodeValue = this.entity.baseName;
        this.extension.nodeValue = this.entity.extension.toLocaleLowerCase();
        this.size.nodeValue = this.entity.size.toHumanString();
        this.permissions.nodeValue = this.entity.permissions;
        this.modified.nodeValue = this.entity.lastModified.toLocaleString();
    }
}
}
{
let
    Object = engine.lang.type.Object,
    Parent = engine.fileManager.view.fileList.item.Parent,
    File = engine.fileManager.view.fileList.item.File;

engine.fileManager.view.fileList.item.Factory = class extends Object {

    createFromDirectory(directory) {
        let items = [];

        if (directory.pattern) {
            items.push(new Parent({
                entity: {
                    id: directory.id,
                    isParent: true
                }
            }));
        } else if (directory.parentId) {
            items.push(new Parent({
                entity: {
                    id: directory.parentId,
                    isParent: true
                }
            }));
        }

        directory.children && directory.children.forEach(entity => {
            let icon = null;
            switch (entity.class) {
                case 'directory':
                    icon = this.icon.create('dir');
                    break;
                case 'file':
                    icon = this.icon.create(entity.type);
                    break;
                case 'hyperlink':
                    icon = this.icon.create('hyperlink');
                    break;
            }

            items.push(new File({
                icon: icon,
                entity: entity
            }));
        });

        return items;
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Open = class extends Observer {

    onOpenKey() {
        if (this.owner.hasActiveItem()) {
            this.owner.trigger('open');
        }
    }

    onRender() {
        this.owner.breadcrumbs.items.forEach(item => {
            item.element.addEventListener('click', () => {
                this.owner.trigger('open', {
                    id: item.id
                });
            });
        });

        this.owner.items.forEach((item) => {
            item.element.addEventListener('dblclick', () => {
                this.owner.trigger('open');
            });
        });
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Select = class extends Observer {

    initialize() {
        super.initialize();
        this.selectedIndexList = [];
        this.clipboard = [];
        this.indexById = {};
    }

    get view() {
        return this.owner;
    }

    get properties() {
        return {
            getActiveIndex: this.getActiveIndex,
            hasActiveItem: this.hasActiveItem,
            getActiveItem: this.getActiveItem,
            activateEntity: this.activateEntity,
            getClipboard: this.getClipboard,
            selectAll: this.selectAll,
            deselectAll: this.deselectAll,
            selectItem: this.selectItem
        };
    }

    onNextItemKey(event) {
        this.activateNextItem(event.shiftKey);
    }

    onPrevItemKey(event) {
        this.activatePrevItem(event.shiftKey);
    }

    onFirstItemKey(event) {
        this.activateFirstItem(event.shiftKey);
    }

    onLastItemKey(event) {
        this.activateLastItem(event.shiftKey);
    }

    onRender() {
        let clipboard = this.clipboard.slice(0);

        this.selectedIndexList = [];
        this.clipboard = [];
        this.indexById = {};

        this.view.items.forEach((item, index) => {
            this.indexById[item.entity.id] = index;
            item.element.addEventListener('click', () => {
                this.activateItem(index);
            });

            // restore clipboard
            if (clipboard.indexOf(item.entity.id) !== -1) {
                this.selectItem(index);
            }
        });
    }

    getActiveIndex() {
        return this.activeIndex;
    };

    getActiveItem() {
        return this.view.items[this.activeIndex];
    }

    hasActiveItem() {
        return !isNaN(this.activeIndex);
    }

    activateEntity(entity) {
        return this.activateItem(this.indexById[entity && entity.id]);
    }

    getClipboard() {
        return this.clipboard;
    }

    selectAll() {
        for (let index = 0; this.view.items[index]; index++) {
            if (this.selectedIndexList.indexOf(index) === -1) {
                this.selectItem(index);
            }
        }
    }

    deselectAll() {
        let selectedIndexList = this.selectedIndexList.slice(0);
        selectedIndexList.forEach(index => {
            this.selectItem(index);
        });
    }

    selectItem(index) {
        if (!this.view.items.length || index < 0 || index >= this.view.items.length) {
            return;
        }

        if (this.view.items[index].entity.isParent) {
            return;
        }

        let
            indexOfIndex = this.selectedIndexList.indexOf(index),
            entity = this.view.items[index].entity;
        if (indexOfIndex === -1) {
            this.selectedIndexList.push(index);
            this.clipboard.push(entity.id);

            this.view.trigger('selectItem', {
                index: index,
                entity: entity
            });
        } else {
            this.selectedIndexList.splice(indexOfIndex, 1);
            this.clipboard.splice(this.clipboard.indexOf(entity.id), 1);

            this.view.trigger('deselectItem', {
                index: index,
                entity: entity
            });
        }
    }

    activateItem(index = 0) {
        if (!this.view.items.length || index < 0 || index >= this.view.items.length) {
            return;
        }

        if (this.view.items[this.activeIndex]) {
            this.view.trigger('deactivateItem', {
                index: this.activeIndex,
                entity: this.view.items[this.activeIndex].entity
            });
        }

        this.activeIndex = index;
        this.view.trigger('activateItem', {
            index: this.activeIndex,
            entity: this.view.items[this.activeIndex].entity
        });
    }

    activatePrevItem(shiftKey) {
        shiftKey && this.selectItem(this.activeIndex);
        this.activateItem(this.activeIndex - 1);
    }

    activateNextItem(shiftKey) {
        shiftKey && this.selectItem(this.activeIndex);
        this.activateItem(this.activeIndex + 1);
    }

    activateFirstItem(shiftKey) {
        if (shiftKey) {
            let index = 0;
            while (this.activeIndex >= index) {
                this.selectItem(index);
                index++;
            }
        }
        this.activateItem(0);
    }

    activateLastItem(shiftKey) {
        if (shiftKey) {
            let index = this.view.items.length - 1;
            while (this.activeIndex <= index) {
                this.selectItem(index);
                index--;
            }
        }
        this.activateItem(this.view.items.length - 1);
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Trash = class extends Observer {

    onTrashKey(keyboardEvent, view) {
        if (view.hasActiveItem()) {
            view.trigger(view.directory.isTrash ? 'remove' : 'trash', {
                index: view.getActiveIndex()
            });
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Remove = class extends Observer {

    onRemoveKey(keyboardEvent, view) {
        if (view.hasActiveItem()) {
            view.trigger('remove', {
                index: view.getActiveIndex()
            });
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Rename = class extends Observer {

    onRenameKey(keyboardEvent, view) {
        if (view.hasActiveItem() && !view.getActiveItem().entity.isParent) {
            view.trigger('rename');
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Permissions = class extends Observer {

    onPermissionsKey(keyboardEvent, view) {
        if (view.hasActiveItem()) {
            view.trigger('permissions');
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Copy = class extends Observer {

    onCopyKey(keyboardEvent, view) {
        if (view.hasActiveItem()) {
            view.trigger('copy');
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Move = class extends Observer {

    onMoveKey(keyboardEvent, view) {
        if (view.hasActiveItem()) {
            view.trigger('move');
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Upload = class extends Observer {

    onUploadKey() {
        this.owner.uploadInput.click();
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Download = class extends Observer {

    onDownloadKey() {
        this.owner.trigger('download');
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.Search = class extends Observer {

    onSearchKey() {
        this.owner.trigger('search');
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.CreateDirectory = class extends Observer {

    onCreateFolderKey() {
        this.owner.trigger('createDirectory');
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.CreateHyperlink = class extends Observer {

    onCreateHyperlinkKey() {
        this.owner.trigger('createHyperlink');
    }
}
}
{
let
    ClassName = engine.gui.utility.ClassName,
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.observer.ListStyle = class extends Observer {

    onActivateItem(event, view) {
        ClassName.add(view.items[event.index].element, 'active');
    }

    onDeactivateItem(event, view) {
        ClassName.remove(view.items[event.index].element, 'active');
    }

    onSelectItem(event, view) {
        ClassName.add(view.items[event.index].element, 'selected');
    }

    onDeselectItem(event, view) {
        ClassName.remove(view.items[event.index].element, 'selected');
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.observer.ListScrolling = class extends Observer {

    onActivateItem(event) {
        let
            body = this.owner.body,
            element = body.items[event.index].element,
            relativeTop = element.getBoundingClientRect().top - body.element.getBoundingClientRect().top,
            screenHeight = body.element.offsetHeight - element.offsetHeight;

        if (relativeTop < 0 || relativeTop >= screenHeight) {
            body.element.scrollTop = element.offsetTop - screenHeight + 5;
        }
    }
}
}
{
let
    Obj = engine.lang.utility.Object,
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.observer.ListSorting = class extends Observer {

    get field() {
        if (!this._field) {
            this._field = 'name';
        }

        return this._field;
    }

    set field(value) {
        this._field = value;
    }

    get desc() {
        if (!this._desc) {
            this._desc = false;
        }

        return this._desc;
    }

    set desc(value) {
        this._desc = value;
    }

    onBeforeRender(event, view) {
        this.sort(view);
    }

    onRender(event, view) {
        view.header.sortBy(this.field, this.desc);

        Obj.forEach(view.header.columns, (field, element) => {
            element.addEventListener('click', () => {
                this.desc = this.field === field ? !this.desc : false;
                this.field = field;

                view.render();
            });
        });
    }

    sort(view) {
        view.directory.children.sort((a, b) => {
            let
                fieldA = a[this.field],
                fieldB = b[this.field];

            if ('size' === this.field) {
                fieldA = fieldA.value;
                fieldB = fieldB.value;
            } else if ('lastModified' === this.field) {
                fieldA = fieldA.getTime();
                fieldB = fieldB.getTime();
            }

            let result = 0;
            if (a.class === 'directory' && b.class === 'directory') {
                result = fieldA > fieldB ? 1 : (fieldB > fieldA ? -1 : 0);
            } else if (a.class === 'directory') {
                return -1;
            } else if (b.class === 'directory') {
                return 1;
            } else {
                result = fieldA > fieldB ? 1 : (fieldB > fieldA ? -1 : 0);
            }

            if (this.desc) {
                result = -result;
            }

            return result;
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.view.fileList.observer.ResizeParent = class extends Object {

    initialize() {
        window.addEventListener('resize', () => {
            this.owner.setHeaderWidth();
        });
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.observer.CheckboxSelection = class extends Observer {

    onSelectItem(event, view) {
        view.items[event.index].checkbox.checked = true;

        let difference = view.directory.parentId ? 1 : 0;

        if (view.getClipboard().length === view.items.length - difference) {
            view.header.checkbox.checked = true;
        }
    }

    onDeselectItem(event, view) {
        view.items[event.index].checkbox.checked = false;

        if (view.getClipboard().length !== view.items.length) {
            view.header.checkbox.checked = false;
        }
    }

    onRender(event, view) {
        view.items.forEach((item, index) => {
            item.checkbox && item.checkbox.addEventListener('change', () => {
                view.selectItem(index);
            });
        });

        view.header.checkbox.addEventListener('change', () => {
            if (view.header.checkbox.checked) {
                view.selectAll();
            } else {
                view.deselectAll();
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.fileList.Layout = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__list');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('input');element2.setAttribute('type', 'file');element2.setAttribute('multiple', 'multiple');element2.setAttribute('style', 'display: none;');this.owner.uploadInput = element2;element0.appendChild(element2);let element3 = document.createTextNode('');element0.appendChild(element3);
    }
}
}
{
let
    View = engine.gui.component.View,
    Activity = engine.gui.trigger.Activity,
    HotKeys = engine.gui.trigger.HotKeys,
    Breadcrumbs = engine.fileManager.view.fileList.breadcrumbs.Layout,
    UploadProgress = engine.fileManager.view.fileList.UploadProgress,
    Header = engine.fileManager.view.fileList.Header,
    Body = engine.fileManager.view.fileList.Body,
    Parent = engine.fileManager.view.fileList.item.Parent,
    ItemsFactory = engine.fileManager.view.fileList.item.Factory,
    Open = engine.fileManager.view.fileList.trigger.Open,
    Select = engine.fileManager.view.fileList.trigger.Select,
    Trash = engine.fileManager.view.fileList.trigger.Trash,
    Remove = engine.fileManager.view.fileList.trigger.Remove,
    Rename = engine.fileManager.view.fileList.trigger.Rename,
    Permissions = engine.fileManager.view.fileList.trigger.Permissions,
    Copy = engine.fileManager.view.fileList.trigger.Copy,
    Move = engine.fileManager.view.fileList.trigger.Move,
    Upload = engine.fileManager.view.fileList.trigger.Upload,
    Download = engine.fileManager.view.fileList.trigger.Download,
    Search = engine.fileManager.view.fileList.trigger.Search,
    CreateDirectory = engine.fileManager.view.fileList.trigger.CreateDirectory,
    CreateHyperlink = engine.fileManager.view.fileList.trigger.CreateHyperlink,
    ListStyle = engine.fileManager.view.fileList.observer.ListStyle,
    ListScrolling = engine.fileManager.view.fileList.observer.ListScrolling,
    ListSorting = engine.fileManager.view.fileList.observer.ListSorting,
    ResizeParent = engine.fileManager.view.fileList.observer.ResizeParent,
    CheckboxSelection = engine.fileManager.view.fileList.observer.CheckboxSelection,
    Template = engine.fileManager.template.fileList.Layout;

engine.fileManager.view.fileList.Layout = class extends View {

    get template() {
        return Template;
    }

    get traits() {
        return [
            Activity,
            HotKeys,
            Open,
            Select,
            Trash,
            Remove,
            Rename,
            Permissions,
            Copy,
            Move,
            Upload,
            Download,
            Search,
            CreateDirectory,
            CreateHyperlink,
            ListStyle,
            ListScrolling,
            ListSorting,
            ResizeParent,
            CheckboxSelection
        ];
    }

    get events() {
        return {
            selectUploadedFiles: {uploadInput: 'change'}
        }
    }

    onSelectUploadedFiles(event) {
        let files = Array.prototype.slice.call(event.target.files || event.dataTransfer.files);
        this.trigger('upload', {
            files: files
        });
        this.uploadInput.value = null;
    }

    initialize() {
        this.itemsFactory = new ItemsFactory({
            icon: this.icon
        });

        this.uploadProgress = new UploadProgress({
            uploadProgressDialog: this.uploadProgressDialog
        });
    }

    render(directory) {
        if (directory) {
            this.directory = directory;
        }

        this.clear();
        this.trigger('beforeRender');

        this.items = this.itemsFactory.createFromDirectory(this.directory);
        this.breadcrumbs = new Breadcrumbs({
            directory: this.directory,
            icon: this.icon
        });
        this.header = new Header({
            icon: this.icon
        });
        this.body = new Body({
            icon: this.icon,
            items: this.items
        });
        this.element.appendChild(this.breadcrumbs.render());
        this.element.appendChild(this.header.element);
        this.element.appendChild(this.body.render());
        this.element.appendChild(this.uploadProgress.element);

        this.setHeaderWidth();
        this.trigger('render');

        return this.element;
    }

    setHeaderWidth() {
        if (this.body.element.clientWidth !== this.body.element.offsetWidth) {
            this.header.element.style.width = this.body.element.clientWidth + 'px';
        }
        if (!this.items.length || (this.items[0] instanceof Parent && 1 === this.items.length)) {
            return;
        }
        let item = this.items[0] instanceof Parent ? this.items[1] : this.items[0];
        item.contents.forEach((content, index) => {
            this.header.contents[index].style.width = content.offsetWidth + 'px';
        });
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.SwitchLeft = class extends Observer {

    onSwitchLeftKey() {
        this.owner.trigger('switch');
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.view.fileList.trigger.SwitchRight = class extends Observer {

    onSwitchRightKey() {
        this.owner.trigger('switch');
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.gui.trigger.Visibility = class extends Object {

    get properties() {
        return {
            show: this.show,
            hide: this.hide
        };
    }

    show(params) {
        this.owner.element.style.display = 'block';
        this.owner.trigger('show', params);
    }

    hide() {
        this.owner.element.style.display = 'none';
        this.owner.trigger('hide');
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.ModalConfirmDialog = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__modal');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__modal-header');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');let element5 = document.createTextNode('');this.owner._caption = element5;this.owner._caption.nodeValue = 'Confirm Dialog';element4.appendChild(element5);element2.appendChild(element4);let element6 = document.createTextNode('');element2.appendChild(element6);let element7 = document.createElement('a');element7.setAttribute('href', '#');element7.setAttribute('title', 'cancel');element7.setAttribute('class', 'engine-fileManager__a engine-fileManager__modal-header-btn close');this.owner.close = element7;element2.appendChild(element7);let element8 = document.createTextNode('');element2.appendChild(element8);element0.appendChild(element2);let element9 = document.createTextNode('');element0.appendChild(element9);let element10 = document.createElement('div');element10.setAttribute('class', 'engine-fileManager__modal-body');let element11 = document.createTextNode('');element10.appendChild(element11);let element12 = document.createElement('div');let element13 = document.createTextNode('');this.owner._message = element13;element12.appendChild(element13);element10.appendChild(element12);let element14 = document.createTextNode('');element10.appendChild(element14);element0.appendChild(element10);let element15 = document.createTextNode('');element0.appendChild(element15);let element16 = document.createElement('div');element16.setAttribute('class', 'engine-fileManager__modal-footer');let element17 = document.createTextNode('');element16.appendChild(element17);let element18 = document.createElement('a');element18.setAttribute('href', '#');element18.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-accent');this.owner.confirm = element18;let element19 = document.createTextNode('');this.owner._confirmCaption = element19;this.owner._confirmCaption.nodeValue = 'Yes';element18.appendChild(element19);element16.appendChild(element18);let element20 = document.createTextNode('');element16.appendChild(element20);let element21 = document.createElement('a');element21.setAttribute('href', '#');element21.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-compliment');this.owner.cancel = element21;let element22 = document.createTextNode('');this.owner._cancelCaption = element22;this.owner._cancelCaption.nodeValue = 'Cancel';element21.appendChild(element22);element16.appendChild(element21);let element23 = document.createTextNode('');element16.appendChild(element23);element0.appendChild(element16);let element24 = document.createTextNode('');element0.appendChild(element24);
    }
}
}
{
let
    View = engine.gui.component.View,
    Activity = engine.gui.trigger.Activity,
    Visibility = engine.gui.trigger.Visibility,
    HotKeys = engine.gui.trigger.HotKeys,
    Template = engine.fileManager.template.control.ModalConfirmDialog;

engine.fileManager.view.control.ModalConfirmDialog = class extends View {

    get template() {
        return Template;
    }

    get traits() {
        return [
            Activity,
            Visibility,
            HotKeys
        ];
    }

    get events() {
        return {
            confirm: {confirm: 'click'},
            cancel: {cancel: 'click', close: 'click'}
        };
    }

    get hotKeys() {
        return {
            confirm: 'Enter',
            cancel: 'Esc'
        }
    }

    set caption(value) {
        this._caption.nodeValue = value;
    }

    set message(value) {
        this._message.nodeValue = value;
    }

    onShow(params) {
        this.params = params;

        this.caption = this.params.caption || '';
        this.message = this.params.message || '';

        this.activate();
    }

    onConfirm() {
        this.hide();
        this.deactivate();
        this.params.onConfirm && this.params.onConfirm();
    }

    onCancel() {
        this.hide();
        this.deactivate();
        this.params.onCancel && this.params.onCancel();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.ModalChooserDialog = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__modal');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__modal-header');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');let element5 = document.createTextNode('');this.owner._caption = element5;this.owner._caption.nodeValue = 'Caption';element4.appendChild(element5);element2.appendChild(element4);let element6 = document.createTextNode('');element2.appendChild(element6);let element7 = document.createElement('a');element7.setAttribute('href', '#');element7.setAttribute('title', 'cancel');element7.setAttribute('class', 'engine-fileManager__a engine-fileManager__modal-header-btn close');this.owner.close = element7;element2.appendChild(element7);let element8 = document.createTextNode('');element2.appendChild(element8);element0.appendChild(element2);let element9 = document.createTextNode('');element0.appendChild(element9);let element10 = document.createElement('div');element10.setAttribute('class', 'engine-fileManager__modal-body');let element11 = document.createTextNode('');element10.appendChild(element11);let element12 = document.createElement('div');let element13 = document.createTextNode('');this.owner._message = element13;element12.appendChild(element13);element10.appendChild(element12);let element14 = document.createTextNode('');element10.appendChild(element14);let element15 = document.createElement('form');element15.setAttribute('action', '#');this.owner.form = element15;element10.appendChild(element15);let element16 = document.createTextNode('');element10.appendChild(element16);element0.appendChild(element10);let element17 = document.createTextNode('');element0.appendChild(element17);let element18 = document.createElement('div');element18.setAttribute('class', 'engine-fileManager__modal-footer');let element19 = document.createTextNode('');element18.appendChild(element19);let element20 = document.createElement('a');element20.setAttribute('href', '#');element20.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-accent');this.owner.confirm = element20;let element21 = document.createTextNode('');this.owner._confirmCaption = element21;this.owner._confirmCaption.nodeValue = 'Ok';element20.appendChild(element21);element18.appendChild(element20);let element22 = document.createTextNode('');element18.appendChild(element22);let element23 = document.createElement('a');element23.setAttribute('href', '#');element23.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-compliment');this.owner.cancel = element23;let element24 = document.createTextNode('');this.owner._cancelCaption = element24;this.owner._cancelCaption.nodeValue = 'Cancel';element23.appendChild(element24);element18.appendChild(element23);let element25 = document.createTextNode('');element18.appendChild(element25);element0.appendChild(element18);let element26 = document.createTextNode('');element0.appendChild(element26);
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.Checkbox = class extends Object {

    initialize() {
        let element0 = document.createElement('div');this.owner._element = element0;element0.setAttribute('style', 'text-align: left');let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('input');element2.setAttribute('type', 'checkbox');element2.setAttribute('value', '');this.owner.input = element2;element0.appendChild(element2);let element3 = document.createTextNode('');element0.appendChild(element3);let element4 = document.createElement('span');let element5 = document.createTextNode('');this.owner._label = element5;this.owner._label.nodeValue = 'Label';element4.appendChild(element5);element0.appendChild(element4);let element6 = document.createTextNode('');element0.appendChild(element6);
    }
}
}
{
let
    View = engine.gui.component.View,
    Template = engine.fileManager.template.control.Checkbox;

engine.fileManager.view.control.Checkbox = class extends View {

    get template() {
        return Template;
    }

    set label(value) {
        this._label.nodeValue = value;
    }

    set value(value) {
        this.input.value = value;
    }

    get value() {
        return this.input.value;
    }

    set checked(value) {
        this.input.checked = value;
    }

    get checked() {
        return this.input.checked;
    }
}
}
{
let
    View = engine.gui.component.View,
    Activity = engine.gui.trigger.Activity,
    Visibility = engine.gui.trigger.Visibility,
    HotKeys = engine.gui.trigger.HotKeys,
    Template = engine.fileManager.template.control.ModalChooserDialog,
    Checkbox = engine.fileManager.view.control.Checkbox,
    Element = engine.gui.utility.Element;

engine.fileManager.view.control.ModalChooserDialog = class extends View {

    get template() {
        return Template;
    }

    get traits() {
        return [
            Activity,
            Visibility,
            HotKeys
        ];
    }

    get events() {
        return {
            confirm: {confirm: 'click'},
            cancel: {cancel: 'click', close: 'click'}
        };
    }

    get hotKeys() {
        return {
            confirm: 'Enter',
            cancel: 'Esc'
        }
    }

    set caption(value) {
        this._caption.nodeValue = value;
    }

    set message(value) {
        this._message.nodeValue = value;
    }

    onShow(params) {
        this.params = params;

        this.caption = this.params.caption || '';
        this.message = this.params.message || '';
        this.checkboxes = [];

        Element.clear(this.form);
        this.params.fileList.forEach((name, index) => {
            let checkbox = new Checkbox({
                label: name,
                value: index,
                checked: true
            });

            this.form.appendChild(checkbox.element);
            this.checkboxes.push(checkbox);
        });

        this.activate();
    }

    onConfirm() {
        this.hide();
        this.deactivate();

        let indexes = [];
        this.checkboxes.forEach(checkbox => {
            indexes[checkbox.value] = checkbox.checked;
        });

        this.params.onConfirm && this.params.onConfirm({
            indexes: indexes
        });
    }

    onCancel() {
        this.hide();
        this.deactivate();
        this.params.onCancel && this.params.onCancel();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.ModalInputDialog = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__modal');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__modal-header');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');let element5 = document.createTextNode('');this.owner._caption = element5;this.owner._caption.nodeValue = 'Caption';element4.appendChild(element5);element2.appendChild(element4);let element6 = document.createTextNode('');element2.appendChild(element6);let element7 = document.createElement('a');element7.setAttribute('href', '#');element7.setAttribute('title', 'cancel');element7.setAttribute('class', 'engine-fileManager__a engine-fileManager__modal-header-btn close');this.owner.close = element7;element2.appendChild(element7);let element8 = document.createTextNode('');element2.appendChild(element8);element0.appendChild(element2);let element9 = document.createTextNode('');element0.appendChild(element9);let element10 = document.createElement('div');element10.setAttribute('class', 'engine-fileManager__modal-body');let element11 = document.createTextNode('');element10.appendChild(element11);let element12 = document.createElement('form');element12.setAttribute('action', '#');let element13 = document.createTextNode('');element12.appendChild(element13);let element14 = document.createElement('label');let element15 = document.createTextNode('');this.owner._label = element15;this.owner._label.nodeValue = 'Label';element14.appendChild(element15);element12.appendChild(element14);let element16 = document.createTextNode('');element12.appendChild(element16);let element17 = document.createElement('input');element17.setAttribute('type', 'text');element17.setAttribute('placeholder', '');this.owner.input = element17;element12.appendChild(element17);let element18 = document.createTextNode('');element12.appendChild(element18);element10.appendChild(element12);let element19 = document.createTextNode('');element10.appendChild(element19);element0.appendChild(element10);let element20 = document.createTextNode('');element0.appendChild(element20);let element21 = document.createElement('div');element21.setAttribute('class', 'engine-fileManager__modal-footer');let element22 = document.createTextNode('');element21.appendChild(element22);let element23 = document.createElement('a');element23.setAttribute('href', '#');element23.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-accent');this.owner.confirm = element23;let element24 = document.createTextNode('');this.owner._confirmCaption = element24;this.owner._confirmCaption.nodeValue = 'Ok';element23.appendChild(element24);element21.appendChild(element23);let element25 = document.createTextNode('');element21.appendChild(element25);let element26 = document.createElement('a');element26.setAttribute('href', '#');element26.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-compliment');this.owner.cancel = element26;let element27 = document.createTextNode('');this.owner._cancelCaption = element27;this.owner._cancelCaption.nodeValue = 'Cancel';element26.appendChild(element27);element21.appendChild(element26);let element28 = document.createTextNode('');element21.appendChild(element28);element0.appendChild(element21);let element29 = document.createTextNode('');element0.appendChild(element29);
    }
}
}
{
let
    View = engine.gui.component.View,
    Activity = engine.gui.trigger.Activity,
    Visibility = engine.gui.trigger.Visibility,
    HotKeys = engine.gui.trigger.HotKeys,
    Template = engine.fileManager.template.control.ModalInputDialog;

engine.fileManager.view.control.ModalInputDialog = class extends View {

    get template() {
        return Template;
    }

    get traits() {
        return [
            Activity,
            Visibility,
            HotKeys
        ];
    }

    get events() {
        return {
            confirm: {confirm: 'click'},
            cancel: {cancel: 'click', close: 'click'}
        };
    }

    get hotKeys() {
        return {
            confirm: 'Enter',
            cancel: 'Esc'
        }
    }

    set caption(value) {
        this._caption.nodeValue = value;
    }

    set label(value) {
        this._label.nodeValue = value;
    }

    set value(value) {
        this.input.value = value;
    }

    set placeholder(value) {
        this.input.placeholder = value;
    }

    onShow(params) {
        this.params = params;

        this.caption = this.params.caption || '';
        this.label = this.params.label || '';
        this.value = this.params.value || '';
        this.placeholder = this.params.placeholder || '';

        this.activate();
        this.input.focus();
    }

    onConfirm() {
        this.hide();
        this.deactivate();

        if (this.input.value === this.params.value) {
            this.params.onCancel && this.params.onCancel();
        } else {
            this.params.onConfirm && this.params.onConfirm({
                value: this.input.value
            });
        }
    }

    onCancel() {
        this.hide();
        this.deactivate();
        this.params.onCancel && this.params.onCancel();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.ModalHyperlinkDialog = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__modal');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__modal-header');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');let element5 = document.createTextNode('');this.owner._caption = element5;this.owner._caption.nodeValue = 'Caption';element4.appendChild(element5);element2.appendChild(element4);let element6 = document.createTextNode('');element2.appendChild(element6);let element7 = document.createElement('a');element7.setAttribute('href', '#');element7.setAttribute('title', 'cancel');element7.setAttribute('class', 'engine-fileManager__a engine-fileManager__modal-header-btn close');this.owner.close = element7;element2.appendChild(element7);let element8 = document.createTextNode('');element2.appendChild(element8);element0.appendChild(element2);let element9 = document.createTextNode('');element0.appendChild(element9);let element10 = document.createElement('div');element10.setAttribute('class', 'engine-fileManager__modal-body');let element11 = document.createTextNode('');element10.appendChild(element11);let element12 = document.createElement('form');element12.setAttribute('action', '#');let element13 = document.createTextNode('');element12.appendChild(element13);let element14 = document.createElement('label');let element15 = document.createTextNode('Name');element14.appendChild(element15);element12.appendChild(element14);let element16 = document.createTextNode('');element12.appendChild(element16);let element17 = document.createElement('input');element17.setAttribute('type', 'text');element17.setAttribute('placeholder', '');this.owner.name = element17;element12.appendChild(element17);let element18 = document.createTextNode('');element12.appendChild(element18);let element19 = document.createElement('label');let element20 = document.createTextNode('Url');element19.appendChild(element20);element12.appendChild(element19);let element21 = document.createTextNode('');element12.appendChild(element21);let element22 = document.createElement('input');element22.setAttribute('type', 'text');element22.setAttribute('placeholder', '');this.owner.url = element22;element12.appendChild(element22);let element23 = document.createTextNode('');element12.appendChild(element23);element10.appendChild(element12);let element24 = document.createTextNode('');element10.appendChild(element24);element0.appendChild(element10);let element25 = document.createTextNode('');element0.appendChild(element25);let element26 = document.createElement('div');element26.setAttribute('class', 'engine-fileManager__modal-footer');let element27 = document.createTextNode('');element26.appendChild(element27);let element28 = document.createElement('a');element28.setAttribute('href', '#');element28.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-accent');this.owner.confirm = element28;let element29 = document.createTextNode('');this.owner._confirmCaption = element29;this.owner._confirmCaption.nodeValue = 'Ok';element28.appendChild(element29);element26.appendChild(element28);let element30 = document.createTextNode('');element26.appendChild(element30);let element31 = document.createElement('a');element31.setAttribute('href', '#');element31.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-compliment');this.owner.cancel = element31;let element32 = document.createTextNode('');this.owner._cancelCaption = element32;this.owner._cancelCaption.nodeValue = 'Cancel';element31.appendChild(element32);element26.appendChild(element31);let element33 = document.createTextNode('');element26.appendChild(element33);element0.appendChild(element26);let element34 = document.createTextNode('');element0.appendChild(element34);
    }
}
}
{
let
    View = engine.gui.component.View,
    Activity = engine.gui.trigger.Activity,
    Visibility = engine.gui.trigger.Visibility,
    HotKeys = engine.gui.trigger.HotKeys,
    Template = engine.fileManager.template.control.ModalHyperlinkDialog;

engine.fileManager.view.control.ModalHyperlinkDialog = class extends View {

    get template() {
        return Template;
    }

    get traits() {
        return [
            Activity,
            Visibility,
            HotKeys
        ];
    }

    get events() {
        return {
            confirm: {confirm: 'click'},
            cancel: {cancel: 'click', close: 'click'}
        };
    }

    get hotKeys() {
        return {
            confirm: 'Enter',
            cancel: 'Esc'
        }
    }

    set caption(value) {
        this._caption.nodeValue = value;
    }

    set placeholderName(value) {
        this.name.placeholder = value;
    }

    set placeholderUrl(value) {
        this.url.placeholder = value;
    }

    onShow(params) {
        this.params = params;

        this.caption = this.params.caption || '';
        this.name.value = this.params.name || '';
        this.url.value = this.params.url || '';
        this.placeholderName = this.params.placeholderName || '';
        this.placeholderUrl = this.params.placeholderUrl || '';

        this.activate();
        this.name.focus();
    }

    onConfirm() {
        this.hide();
        this.deactivate();

        if (
            this.name.value === this.params.name &&
            this.url.value === this.params.url
        ) {
            this.params.onCancel && this.params.onCancel();
        } else {
            this.params.onConfirm && this.params.onConfirm({
                name: this.name.value,
                url: this.url.value
            });
        }
    }

    onCancel() {
        this.hide();
        this.deactivate();
        this.params.onCancel && this.params.onCancel();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.uploadProgress.Item = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__progress');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__progress-bar');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');element4.setAttribute('class', 'engine-fileManager__progress-bar-line');this.owner.line = element4;element4.setAttribute('style', 'width: 0');element2.appendChild(element4);let element5 = document.createTextNode('');element2.appendChild(element5);let element6 = document.createElement('div');element6.setAttribute('class', 'engine-fileManager__progress-percent');let element7 = document.createTextNode('');this.owner.percent = element7;this.owner.percent.nodeValue = '0%';element6.appendChild(element7);element2.appendChild(element6);let element8 = document.createTextNode('');element2.appendChild(element8);let element9 = document.createElement('a');element9.setAttribute('title', 'cancel');element9.setAttribute('class', 'engine-fileManager__a engine-fileManager__progress-remove');this.owner.marker = element9;element2.appendChild(element9);let element10 = document.createTextNode('');element2.appendChild(element10);element0.appendChild(element2);let element11 = document.createTextNode('');element0.appendChild(element11);let element12 = document.createElement('div');element12.setAttribute('class', 'engine-fileManager__progress-title');let element13 = document.createTextNode('');this.owner._title = element13;element12.appendChild(element13);element0.appendChild(element12);let element14 = document.createTextNode('');element0.appendChild(element14);
    }
}
}
{
let
    View = engine.gui.component.View,
    ClassName = engine.gui.utility.ClassName,
    Template = engine.fileManager.template.control.uploadProgress.Item;

engine.fileManager.view.control.uploadProgress.Item = class extends View {

    get template() {
        return Template;
    }

    get events() {
        return {
            abort: {marker: 'click'}
        }
    }

    set title(value) {
        this._title.nodeValue = value;
    }

    update(percent) {
        this.line.style.width = percent + '%';
        this.percent.nodeValue = percent + '%';

        if (100 === percent) {
            ClassName.set(this.marker, 'engineFM__progress-done done');
        }
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.uploadProgress.TotalItem = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__progress total');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__progress-bar');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');element4.setAttribute('class', 'engine-fileManager__progress-bar-line');this.owner.line = element4;element4.setAttribute('style', 'width: 0');element2.appendChild(element4);let element5 = document.createTextNode('');element2.appendChild(element5);let element6 = document.createElement('div');element6.setAttribute('class', 'engine-fileManager__progress-percent');let element7 = document.createTextNode('');this.owner.percent = element7;this.owner.percent.nodeValue = '0%';element6.appendChild(element7);element2.appendChild(element6);let element8 = document.createTextNode('');element2.appendChild(element8);let element9 = document.createElement('div');element9.setAttribute('class', 'engine-fileManager__progress-done');this.owner.marker = element9;element2.appendChild(element9);let element10 = document.createTextNode('');element2.appendChild(element10);element0.appendChild(element2);let element11 = document.createTextNode('');element0.appendChild(element11);let element12 = document.createElement('div');element12.setAttribute('class', 'engine-fileManager__progress-title');let element13 = document.createTextNode('');this.owner._title = element13;this.owner._title.nodeValue = 'Total';element12.appendChild(element13);element0.appendChild(element12);let element14 = document.createTextNode('');element0.appendChild(element14);
    }
}
}
{
let
    Item = engine.fileManager.view.control.uploadProgress.Item,
    Template = engine.fileManager.template.control.uploadProgress.TotalItem;

engine.fileManager.view.control.uploadProgress.TotalItem = class extends Item {

    get template() {
        return Template;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.uploadProgress.ModalDialog = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__modal');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('div');element2.setAttribute('class', 'engine-fileManager__modal-header');let element3 = document.createTextNode('');element2.appendChild(element3);let element4 = document.createElement('div');let element5 = document.createTextNode('');this.owner._caption = element5;this.owner._caption.nodeValue = 'Caption';element4.appendChild(element5);element2.appendChild(element4);let element6 = document.createTextNode('');element2.appendChild(element6);let element7 = document.createElement('a');element7.setAttribute('href', '#');element7.setAttribute('title', 'cancel');element7.setAttribute('class', 'engine-fileManager__a engine-fileManager__modal-header-btn close');this.owner.close = element7;element2.appendChild(element7);let element8 = document.createTextNode('');element2.appendChild(element8);element0.appendChild(element2);let element9 = document.createTextNode('');element0.appendChild(element9);let element10 = document.createElement('div');element10.setAttribute('class', 'engine-fileManager__modal-body');this.owner.body = element10;element0.appendChild(element10);let element11 = document.createTextNode('');element0.appendChild(element11);let element12 = document.createElement('div');element12.setAttribute('class', 'engine-fileManager__modal-footer');let element13 = document.createTextNode('');element12.appendChild(element13);let element14 = document.createElement('a');element14.setAttribute('href', '#');element14.setAttribute('class', 'engine-fileManager__a engine-fileManager__btn engine-fileManager__btn-accent');this.owner.toBackground = element14;let element15 = document.createTextNode('');this.owner._toBackgroundCaption = element15;this.owner._toBackgroundCaption.nodeValue = 'To Background';element14.appendChild(element15);element12.appendChild(element14);let element16 = document.createTextNode('');element12.appendChild(element16);element0.appendChild(element12);let element17 = document.createTextNode('');element0.appendChild(element17);
    }
}
}
{
let
    View = engine.gui.component.View,
    Activity = engine.gui.trigger.Activity,
    Visibility = engine.gui.trigger.Visibility,
    HotKeys = engine.gui.trigger.HotKeys,
    Item = engine.fileManager.view.control.uploadProgress.Item,
    TotalItem = engine.fileManager.view.control.uploadProgress.TotalItem,
    Template = engine.fileManager.template.control.uploadProgress.ModalDialog;

engine.fileManager.view.control.uploadProgress.ModalDialog = class extends View {

    get template() {
        return Template;
    }

    get traits() {
        return [
            Activity,
            Visibility,
            HotKeys
        ];
    }

    get events() {
        return {
            toBackground: {toBackground: 'click', close: 'click'}
        };
    }

    get hotKeys() {
        return {
            toBackground: 'Enter'
        }
    }

    initialize() {
        this.total = new TotalItem({
            title: 'Total',
            icon: this.icon
        });
        this.body.appendChild(this.total.element);
    }

    set caption(value) {
        if (null !== value) {
            this._caption.nodeValue = value;
        }
    }

    show(params = {}) {
        this.params = params;
        this.caption = this.params.caption || null;

        this.activate();
        super.show();
    }

    appendItem(params) {
        params.icon = this.icon;
        let item = new Item(params);
        this.body.appendChild(item.element);

        return item;
    }

    removeItem(item) {
        this.body.removeChild(item.element);
    }

    hide() {
        super.hide();
        this.deactivate();
    }

    onToBackground() {
        this.hide();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.control.RequestProgress = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager__request-progress');this.owner._element = element0;let element1 = document.createTextNode('');element0.appendChild(element1);let element2 = document.createElement('i');element2.setAttribute('class', 'icon-spin4 animate-spin');element0.appendChild(element2);let element3 = document.createTextNode('');element0.appendChild(element3);
    }
}
}
{
let
    View = engine.gui.component.View,
    Visibility = engine.gui.trigger.Visibility,
    Template = engine.fileManager.template.control.RequestProgress;

engine.fileManager.view.control.RequestProgress = class extends View {

    get template() {
        return Template;
    }

    get traits() {
        return [
            Visibility
        ];
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.template.Layout = class extends Object {

    initialize() {
        let element0 = document.createElement('div');element0.setAttribute('class', 'engine-fileManager');this.owner._element = element0;
    }
}
}
{
let
    View = engine.gui.component.View,
    IconFactory = engine.fileManager.view.icon.Factory,
    Toolbar = engine.fileManager.view.Toolbar,
    List = engine.fileManager.view.fileList.Layout,
    SwitchLeft = engine.fileManager.view.fileList.trigger.SwitchLeft,
    SwitchRight = engine.fileManager.view.fileList.trigger.SwitchRight,
    ConfirmDialog = engine.fileManager.view.control.ModalConfirmDialog,
    ChooserDialog = engine.fileManager.view.control.ModalChooserDialog,
    InputDialog = engine.fileManager.view.control.ModalInputDialog,
    HyperlinkDialog = engine.fileManager.view.control.ModalHyperlinkDialog,
    UploadProgressDialog = engine.fileManager.view.control.uploadProgress.ModalDialog,
    RequestProgress = engine.fileManager.view.control.RequestProgress,
    Template = engine.fileManager.template.Layout;

engine.fileManager.view.Layout = class extends View {

    get template() {
        return Template;
    }

    initialize() {
        let icon = new IconFactory({
            iconClasses: this.config.icons,
            defaultIconClass: this.config.defaultIcon
        });

        this.toolbar = new Toolbar({
            icon: icon,
            hotKeys: this.config.hotKeys
        });

        this.uploadProgressDialog = new UploadProgressDialog({
            icon: icon
        });

        this.leftList = new List({
            hotKeys: this.config.hotKeys,
            uploadProgressDialog: this.uploadProgressDialog,
            icon: icon
        });
        this.leftList.use(SwitchRight);

        this.rightList = new List({
            hotKeys: this.config.hotKeys,
            uploadProgressDialog: this.uploadProgressDialog,
            icon: icon
        });
        this.rightList.use(SwitchLeft);

        this.confirmDialog = new ConfirmDialog({
            icon: icon
        });

        this.inputDialog = new InputDialog({
            icon: icon
        });

        this.hyperlinkDialog = new HyperlinkDialog({
            icon: icon
        });

        this.chooserDialog = new ChooserDialog({
            icon: icon
        });

        this.requestProgress = new RequestProgress();
    }

    render() {
        this.clear();

        this.element.appendChild(this.toolbar.render());
        this.element.appendChild(this.leftList.element);
        this.element.appendChild(this.rightList.element);
        this.element.appendChild(this.inputDialog.element);
        this.element.appendChild(this.hyperlinkDialog.element);
        this.element.appendChild(this.confirmDialog.element);
        this.element.appendChild(this.chooserDialog.element);
        this.element.appendChild(this.uploadProgressDialog.element);
        this.element.appendChild(this.requestProgress.element);

        this.appendTo(this.wrapper);
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.web.component.Model = class extends Object {

    request(request, ...observers) {
        this.client.exchange(request, ...observers);
    }

    get(request, ...observers) {
        request.method = 'GET';
        this.request(request, ...observers);
    }

    post(request, ...observers) {
        request.method = 'POST';
        this.request(request, ...observers);
    }
}
}
{
let
    Model = engine.web.component.Model;

engine.fileManager.model.File = class extends Model {

    open(params) {
        this.client.open('open', {id: params.id}, '_blank');
    }

    openHyperlink(params) {
        window.open(params.url, '_blank');
    }

    download(params) {
        this.client.open('download', {id: params.id});
    }

    read(bodyParams, ...observers) {
        this.post({
            target: bodyParams.pattern ? 'search' : 'read',
            bodyParams: bodyParams
        }, ...observers);
    }

    upload(form, ...observers) {
        this.post({
            target: 'upload',
            body: form
        }, ...observers);
    }

    copy(params, ...observers) {
        this.post({
            target: 'copy',
            bodyParams: {
                id: params.id,
                parentId: params.parentId
            }
        }, ...observers);
    }

    move(params, ...observers) {
        this.post({
            target: 'move',
            bodyParams: {
                id: params.id,
                parentId: params.parentId
            }
        }, ...observers);
    }

    createDirectory(params, ...observers) {
        this.post({
            target: 'create/directory',
            bodyParams: {
                parentId: params.parentId,
                name: params.name
            }
        }, ...observers);
    }

    createHyperlink(params, ...observers) {
        this.post({
            target: 'create/hyperlink',
            bodyParams: {
                parentId: params.parentId,
                name: params.name,
                url: params.url
            }
        }, ...observers);
    }

    rename(params, ...observers) {
        this.post({
            target: 'rename',
            bodyParams: {
                id: params.id,
                name: params.name
            }
        }, ...observers);
    }

    setPermissions(params, ...observers) {
        this.post({
            target: 'permissions',
            bodyParams: {
                id: params.id,
                permissions: params.permissions
            }
        }, ...observers);
    }

    remove(params, ...observers) {
        this.post({
            target: 'remove',
            bodyParams: {
                id: params.id
            }
        }, ...observers);
    }

    trash(params, ...observers) {
        this.post({
            target: 'trash',
            bodyParams: {
                id: params.id
            }
        }, ...observers);
    }
}
}
{
let
    Component = engine.react.Component,
    Obj = engine.lang.utility.Object,
    Type = engine.lang.utility.Type;

engine.web.component.Controller = class extends Component {

    _constructor(...args) {
        super._constructor(...args);
        this.actionMap = this.actions || {};
        this.subscribeActions(this.actionMap);
    }

    subscribeActions(actions) {
        Obj.forEach(actions, (eventName, action) => {
            this.subscribeAction(eventName, action);
        });
    }

    subscribeAction(eventName, action) {
        this.actionMap[eventName] = action;
        this.on(eventName, (...args) => {
            this.executeAction(eventName, ...args);
        });
    }

    getAction(eventName) {
        let action = this.actionMap[eventName];
        if (Type.isObject(action)) {
            return action;
        }
        action = new action(this.properties || {}, this);
        this.actionMap[eventName] = action;

        return action;
    }

    executeAction(eventName, ...args) {
        if (!this.isActionAllowed(eventName)) {
            return;
        }
        try {
            this.getAction(eventName).execute(...args);
        } catch (throwable) {
            this.handleActionError(throwable);
        }
    }

    isActionAllowed(eventName) {
        return true;
    }

    handleActionError(throwable) {
        throw throwable;
    }

    render(data) {
        this.view.set(data);
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.controller.fileList.observer.SelfInteraction = class extends Observer {

    onRender(event, controller) {
        if (controller.isActive || controller.getDirectory().id === event.entity.id) {
            controller.setDirectory(event.entity);
        }
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.controller.fileList.observer.DoubleInteraction = class extends Observer {

    onActivate(event, controller) {
        controller.getDouble() && controller.getDouble().deactivate();
    }
}
}
{
let
    Observer = engine.react.Observer;

engine.fileManager.controller.fileList.observer.ToolbarInteraction = class extends Observer {

    get properties() {
        return {
            affectToolbar: this.affectToolbar
        };
    }

    affectToolbar() {
        if (this.owner.getActiveEntity()) {
            let
                isParent = this.owner.getActiveEntity().isParent,
                emptyClipboard = !this.owner.view.getClipboard().length,
                toolbar = this.owner.toolbar;

            toolbar.trash.disabled(isParent && emptyClipboard || this.owner.getDirectory().isTrash);
            toolbar.remove.disabled(isParent && emptyClipboard);
            toolbar.copy.disabled(isParent && emptyClipboard);
            toolbar.move.disabled(isParent && emptyClipboard);
            toolbar.download.disabled(isParent && emptyClipboard);
            toolbar.rename.disabled(isParent);
            toolbar.permissions.disabled(isParent);
        }
    }

    onActivate(event, controller) {
        controller.affectToolbar();
    }

    onRender(event, controller) {
        if (controller.isActive) {
            let
                hasChildren = event.entity.children && event.entity.children.length,
                toolbar = controller.toolbar;

            toolbar.open.disabled(!hasChildren && !event.entity.parentId);
            toolbar.trash.disabled(!hasChildren || event.entity.isTrash);
            toolbar.remove.disabled(!hasChildren);
            toolbar.copy.disabled(!hasChildren);
            toolbar.move.disabled(!hasChildren);
            toolbar.rename.disabled(!hasChildren);
            toolbar.permissions.disabled(!hasChildren);
            toolbar.download.disabled(!hasChildren);

        }
    }
}
}
{
let
    ClassName = engine.gui.utility.ClassName,
    Observer = engine.react.Observer;

engine.fileManager.controller.fileList.observer.ViewInteraction = class extends Observer {

    onActivate(event, controller) {
        ClassName.add(controller.view.element, 'active');
    }

    onDeactivate(event, controller) {
        ClassName.remove(controller.view.element, 'active');
    }

    onRender(event, controller) {
        if (controller.isActive || controller.getDirectory().id === event.entity.id) {
            controller.view.render(event.entity);
            controller.view.activateEntity(controller.getActiveEntity());
        }
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Initialize = class extends Object {

    execute() {
        this.fileModel.read({
            id: this.getDirectory().id
        }, this);
    }

    onSuccess(event) {
        this.keepLastDirectory(event.entity);
        this.render(event);

        this.controller.trigger('initialize');
    }

    onStatusCode404() {
        this.setDirectory({id: '/'});
        this.execute();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Open = class extends Object {

    execute(event) {
        if (event && event.id) {
            this.fileModel.read({
                id: event.id
            }, this);

            return;
        }

        switch (this.getActiveEntity().class) {
            case 'file':
                this.fileModel.open({
                    id: this.getActiveEntity().id
                });
                return;
            case 'hyperlink':
                this.fileModel.openHyperlink({
                    url: this.getActiveEntity().url
                });
                return;
            default:
                this.fileModel.read({
                    id: this.getActiveEntity().id
                }, this);
        }
    }

    onSuccess(event) {
        this.selectIfParent(event.entity);
        this.keepLastDirectory(event.entity);
        this.render(event);
        this.view.activate();
    }

    selectIfParent(entity) {
        if (entity.id === this.getDirectory().parentId) {
            this.setActiveEntity(this.getDirectory());
        }
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.OpenTrash = class extends Object {

    execute() {
        this.fileModel.read({
            id: '/$trash'
        }, this);
    }

    onSuccess(event) {
        this.render(event);
        this.view.activate();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Search = class extends Object {

    execute() {
        this.layout.inputDialog.show({
            icon: 'search',
            caption: 'Search',
            label: 'Keyword',
            value: '',
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: event => {
                this.fileModel.read({
                    id: this.getDirectory().id,
                    pattern: event.value
                }, this);
            }
        });
    }

    onSuccess(event) {
        this.render(event);
        this.view.activate();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Download = class extends Object {

    execute() {
        let clipboard = this.view.getClipboard();
        if ((!this.getActiveEntity() || this.getActiveEntity().isParent) && !clipboard.length) {
            return;
        }

        this.fileModel.download({
            id: clipboard.length ? clipboard : this.getActiveEntity().id
        });
    }
}
}
{
let
    Object = engine.lang.type.Object,
    Str = engine.lang.utility.String;

let self = engine.fileManager.controller.fileList.action.Upload = class extends Object {

    execute(event) {
        this.view.activate();
        let files = event.files;

        if (!this.getDirectory().hasChildren()) {
            this.upload(files);
            return;
        }

        let matches = [];
        files.forEach((file, index) => {
            if (this.getDirectory().containsName(files[index].name)) {
                matches[index] = files[index].name;
            }
        });

        if (!matches.length) {
            this.upload(files);
            return;
        }

        this.layout.chooserDialog.show({
            caption: 'Confirm Dialog',
            message: 'The following files are already exist. Please select files to reloading.',
            fileList: matches,
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: event => {
                this.view.activate();
                let counter = 0;
                event.indexes.forEach((isSelected, index) => {
                    if (!isSelected) {
                        files.splice(index - counter, 1);
                        counter++;
                    }
                });
                this.upload(files);
            }
        });
    }

    upload(files) {
        this.validateFiles(files);
        let
            directoryId = this.getDirectory().id,
            shortProgressView = this.view.uploadProgress,
            progressDialog = this.layout.uploadProgressDialog,
            total = 0,
            loaded = 0;

        progressDialog.show({
            caption: 'Upload Files'
        });

        files.forEach(file => {
            let
                form = new FormData(),
                itemView = null,
                removeRequest = event => {
                    let request = event.request;

                    total -= request.progress.total;
                    loaded -= request.progress.loaded;

                    if (total > 0) {
                        progressDialog.total.update(self.align(100 * loaded / total));
                        shortProgressView.update(self.align(100 * loaded / total));
                    } else {
                        progressDialog.total.update(0);
                        shortProgressView.update(0);
                        progressDialog.hide();
                        this.view.activate();
                    }
                    progressDialog.removeItem(itemView);

                    if (directoryId === this.getDirectory().id) {
                        this.fileModel.read({
                            id: directoryId
                        }, {
                            onSuccess: event => {
                                this.render(event);
                                if (this.getDouble() && directoryId === this.getDouble().getDirectory().id) {
                                    this.getDouble().render(event);
                                }
                            }
                        });
                    }
                };

            form.append('file', file);
            form.append('parentId', directoryId);

            this.fileModel.upload(form, {
                onOpen: event => {
                    let request = event.request;

                    itemView = progressDialog.appendItem({
                        title: file.name,
                        onAbort: () => {
                            request.abort();
                        }
                    });
                },
                onProgress: event => {
                    let request = event.request;

                    if (!request.prevProgress) {
                        total += request.progress.total;
                        loaded += request.progress.loaded;
                    } else {
                        loaded += request.progress.loaded - request.prevProgress.loaded;
                    }
                    progressDialog.total.update(self.align(100 * loaded / total));
                    shortProgressView.update(self.align(100 * loaded / total));
                    itemView.update(self.align(100 * request.progress.loaded / request.progress.total));
                },
                onSuccess: removeRequest,
                onAbort: removeRequest
            });
        });
    }

    validateFiles(files) {
        if (!this.config.isNumberOfUploadFilesAllowed(files.length)) {
            throw Str.format('The files number exceeds the allowed number {0}.', this.config.maxNumberOfUploadFiles);
        }

        files.forEach(file => {
            if (!this.config.isMimeTypeAllowed(file.type)) {
                throw Str.format('The file \'{0}\' has unsupported type \'{1}\'.', file.name, file.type);
            }
            if (!this.config.isFileSizeAllowed(file.size)) {
                throw Str.format('The file \'{0}\' exceeds the allowed size {1}.', file.name, this.config.uploadMaxFileSize.toHumanString());
            }
        })
    }

    static align(value) {
        return Math.ceil((value) * 100) / 100;
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.CreateDirectory = class extends Object {

    execute() {
        this.layout.inputDialog.show({
            icon: 'dir',
            caption: 'Create Folder',
            label: 'Folder Name',
            placeholder: 'NewFolder',
            value: '',
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: event => {
                this.fileModel.createDirectory({
                    name: event.value,
                    parentId: this.getDirectory().id
                }, this);
            }
        });
    }

    onSuccess(event) {
        this.setActiveEntity(event.entity);

        this.fileModel.read({
            id: this.getDirectory().id
        }, {
            onSuccess: event => {
                this.render(event);
                this.view.activate();
                this.getDouble() && this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.CreateHyperlink = class extends Object {

    execute() {
        this.layout.hyperlinkDialog.show({
            caption: 'Create Hyperlink',
            name: '',
            url: '',
            placeholderName: 'MyFavoriteSite',
            placeholderUrl: 'http://example.com',
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: event => {
                this.fileModel.createHyperlink({
                    parentId: this.getDirectory().id,
                    name: event.name,
                    url: event.url
                }, this);
            }
        });
    }

    onSuccess(event) {
        this.setActiveEntity(event.entity);

        this.fileModel.read({
            id: this.getDirectory().id
        }, {
            onSuccess: event => {
                this.render(event);
                this.view.activate();
                this.getDouble() && this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object,
    Hyperlink = engine.fileManager.entity.Hyperlink;

engine.fileManager.controller.fileList.action.Rename = class extends Object {

    execute() {
        let entity = this.getActiveEntity(),
            value = entity instanceof Hyperlink ? entity.baseName : entity.name;

        this.layout.inputDialog.show({
            icon: 'edit',
            caption: 'Rename',
            label: 'New Name',
            placeholder: value,
            value: value,
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: event => {
                this.fileModel.rename({
                    id: this.getActiveEntity().id,
                    name: entity instanceof Hyperlink ? event.value + '.' + entity.extension : event.value
                }, this);
            }
        });
    }

    onSuccess(event) {
        if (
            this.getDouble() &&
            this.getDouble().activeEntity &&
            this.getDouble().activeEntity.id === this.getActiveEntity().id
        ) {
            this.getDouble().setActiveEntity(event.entity);
        }
        this.setActiveEntity(event.entity);

        this.fileModel.read({
            id: this.getDirectory().id
        }, {
            onSuccess: event => {
                this.render(event);
                this.view.activate();
                this.getDouble() && this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.SetPermissions = class extends Object {

    execute() {
        let value = this.getActiveEntity().permissions;

        this.layout.inputDialog.show({
            icon: 'permissions',
            caption: 'Permissions',
            label: 'New Permissions',
            placeholder: value,
            value: value,
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: event => {
                this.fileModel.setPermissions({
                    id: this.getActiveEntity().id,
                    permissions: event.value
                }, this);
            }
        });
    }

    onSuccess(event) {
        if (
            this.getDouble() &&
            this.getDouble().activeEntity &&
            this.getDouble().activeEntity.id === this.getActiveEntity().id
        ) {
            this.getDouble().activeEntity = event.entity;
        }
        this.setActiveEntity(event.entity);

        this.fileModel.read({
            id: this.getDirectory().id
        }, {
            onSuccess: event => {
                this.render(event);
                this.view.activate();
                this.getDouble() && this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Copy = class extends Object {

    execute() {
        let clipboard = this.view.getClipboard();
        if ((!this.getActiveEntity() || this.getActiveEntity().isParent) && !clipboard.length) {
            return;
        }

        if (this.getDouble()) {
            this.fileModel.copy({
                id: clipboard.length ? clipboard : this.getActiveEntity().id,
                parentId: this.getDouble().getDirectory().id
            }, this);
        }
    }

    onSuccess() {
        this.getDouble().fileModel.read({
            id: this.getDouble().getDirectory().id
        }, {
            onSuccess: event => {
                this.view.deselectAll();
                this.view.activate();
                this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Move = class extends Object {

    execute() {
        let clipboard = this.view.getClipboard();
        if ((!this.getActiveEntity() || this.getActiveEntity().isParent) && !clipboard.length) {
            return;
        }

        if (this.getDouble() && this.getDirectory().id !== this.getDouble().getDirectory().id) {
            this.fileModel.move({
                id: clipboard.length ? clipboard : this.getActiveEntity().id,
                parentId: this.getDouble().getDirectory().id
            }, this);
        }
    }

    onSuccess(event) {
        for (let i = 0; event.collection[i]; i++) {
            if (this.getActiveEntity().id === event.collection[i].id) {
                this.setActiveEntity(null);
                break;
            }
        }

        this.fileModel.read({
            id: this.getDirectory().id
        }, {
            onSuccess: event => {
                this.render(event);
                this.view.activate();
            }
        });

        this.getDouble().fileModel.read({
            id: this.getDouble().getDirectory().id
        }, {
            onSuccess: event => {
                this.view.deselectAll();
                this.view.activate();
                this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Trash = class extends Object {

    execute() {
        let clipboard = this.view.getClipboard();
        if ((!this.getActiveEntity() || this.getActiveEntity().isParent) && !clipboard.length) {
            return;
        }

        this.layout.confirmDialog.show({
            caption: 'Confirm Dialog',
            message: 'Are you sure to trash selected items?',
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: () => {
                this.fileModel.trash({
                    id: clipboard.length ? clipboard : this.getActiveEntity().id
                }, this);
            }
        });
    }

    onSuccess(event) {
        let entity = event.collection[0];
        if (this.getDouble() && this.getDouble().getDirectory().inBreadcrumbs(entity.id)) {
            this.getDouble().setDirectory({
                id: entity.parentId
            });
            this.getDouble().keepLastDirectory(this.getDouble().getDirectory());
        }

        for (let i = 0; event.collection[i]; i++) {
            if (this.getActiveEntity().id === event.collection[i].id) {
                this.setActiveEntity(null);
                break;
            }
        }

        this.fileModel.read({
            id: this.getDirectory().id
        }, {
            onSuccess: event => {
                this.render(event);
                this.view.activate();
                this.getDouble() && this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.Remove = class extends Object {

    execute() {
        let clipboard = this.view.getClipboard();
        if ((!this.getActiveEntity() || this.getActiveEntity().isParent) && !clipboard.length) {
            return;
        }

        this.layout.confirmDialog.show({
            caption: 'Confirm Dialog',
            message: 'Are you sure to delete selected items permanently?',
            onCancel: () => {
                this.view.activate();
            },
            onConfirm: () => {
                this.fileModel.remove({
                    id: clipboard.length ? clipboard : this.getActiveEntity().id
                }, this);
            }
        });
    }

    onSuccess(event) {
        let entity = event.collection[0];
        if (this.getDouble() && this.getDouble().getDirectory().inBreadcrumbs(entity.id)) {
            this.getDouble().setDirectory({
                id: entity.parentId
            });
            this.getDouble().keepLastDirectory(this.getDouble().getDirectory());
        }

        for (let i = 0; event.collection[i]; i++) {
            if (this.getActiveEntity().id === event.collection[i].id) {
                this.setActiveEntity(null);
                break;
            }
        }

        this.fileModel.read({
            id: this.getDirectory().id
        }, {
            onSuccess: event => {
                this.render(event);
                this.view.activate();
                this.getDouble() && this.getDouble().render(event);
            }
        });
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.ActivateItem = class extends Object {

    execute(event) {
        this.setActiveEntity(event.entity);
        this.affectToolbar();
    }
}
}
{
let
    Object = engine.lang.type.Object;

engine.fileManager.controller.fileList.action.SwitchList = class extends Object {

    execute() {
        if (this.getDouble()) {
            this.getDouble().view.activate();
            this.getDouble().view.activateEntity(this.getDouble().activeEntity);
        }
    }
}
}
{
let
    WebController = engine.web.component.Controller,
    SelfInteraction = engine.fileManager.controller.fileList.observer.SelfInteraction,
    DoubleInteraction = engine.fileManager.controller.fileList.observer.DoubleInteraction,
    ToolbarInteraction = engine.fileManager.controller.fileList.observer.ToolbarInteraction,
    ViewInteraction = engine.fileManager.controller.fileList.observer.ViewInteraction,
    Initialize = engine.fileManager.controller.fileList.action.Initialize,
    Open = engine.fileManager.controller.fileList.action.Open,
    OpenTrash = engine.fileManager.controller.fileList.action.OpenTrash,
    Search = engine.fileManager.controller.fileList.action.Search,
    Download = engine.fileManager.controller.fileList.action.Download,
    Upload = engine.fileManager.controller.fileList.action.Upload,
    CreateDirectory = engine.fileManager.controller.fileList.action.CreateDirectory,
    CreateHyperlink = engine.fileManager.controller.fileList.action.CreateHyperlink,
    Rename = engine.fileManager.controller.fileList.action.Rename,
    SetPermissions = engine.fileManager.controller.fileList.action.SetPermissions,
    Copy = engine.fileManager.controller.fileList.action.Copy,
    Move = engine.fileManager.controller.fileList.action.Move,
    Trash = engine.fileManager.controller.fileList.action.Trash,
    Remove = engine.fileManager.controller.fileList.action.Remove,
    ActivateItem = engine.fileManager.controller.fileList.action.ActivateItem,
    SwitchList = engine.fileManager.controller.fileList.action.SwitchList;

engine.fileManager.controller.FileList = class extends WebController {

    constructor(...args) {
        super(...args);
        this.trigger('ready');
    }

    get properties() {
        return {
            controller: this,
            user: this.user,
            config: this.config,
            fileModel: this.fileModel,
            view: this.view,
            layout: this.layout,
            getActiveEntity: this.getActiveEntity,
            setActiveEntity: this.setActiveEntity,
            getDirectory: this.getDirectory,
            setDirectory: this.setDirectory,
            getDouble: this.getDouble,
            affectToolbar: this.affectToolbar,
            render: this.render,
            keepLastDirectory: this.keepLastDirectory
        };
    }

    get traits() {
        return [
            SelfInteraction,
            DoubleInteraction,
            ToolbarInteraction,
            ViewInteraction
        ];
    }

    get events() {
        return {
            activate: {view: 'activate'},
            open: {view: 'open', toolbar: 'open'},
            openTrash: {view: 'openTrash', toolbar: 'openTrash'},
            search: {view: 'search', toolbar: 'search'},
            download: {view: 'download', toolbar: 'download'},
            upload: {view: 'upload', toolbar: 'upload'},
            createDirectory: {view: 'createDirectory', toolbar: 'createDirectory'},
            createHyperlink: {view: 'createHyperlink', toolbar: 'createHyperlink'},
            rename: {view: 'rename', toolbar: 'rename'},
            setPermissions: {view: 'permissions', toolbar: 'permissions'},
            copy: {view: 'copy', toolbar: 'copy'},
            move: {view: 'move', toolbar: 'move'},
            trash: {view: 'trash', toolbar: 'trash'},
            remove: {view: 'remove', toolbar: 'remove'},
            activateItem: {view: 'activateItem'},
            switchList: {view: 'switch'}
        };
    }

    get actions() {
        return {
            ready: Initialize,
            open: Open,
            openTrash: OpenTrash,
            search: Search,
            download: Download,
            upload: Upload,
            createDirectory: CreateDirectory,
            createHyperlink: CreateHyperlink,
            rename: Rename,
            setPermissions: SetPermissions,
            copy: Copy,
            move: Move,
            trash: Trash,
            remove: Remove,
            activateItem: ActivateItem,
            switchList: SwitchList
        };
    }

    isActionAllowed(eventName) {
        return this.isActive || 'ready' === eventName;
    }

    get toolbar() {
        return this.layout.toolbar;
    }

    getActiveEntity() {
        return this.activeEntity;
    }

    setActiveEntity(value) {
        this.activeEntity = value;
    }

    getDirectory() {
        return this.directory;
    }

    setDirectory(value) {
        this.directory = value;
    }

    getDouble() {
        return this._double;
    }

    setDouble(instance) {
        this._double = instance;
        instance._double = this;
    }

    keepLastDirectory(entity) {
        if (this.layout.leftList === this.view) {
            this.user.write('leftDirectory', {id: entity.id});
        } else {
            this.user.write('rightDirectory', {id: entity.id});
        }
    }

    handleActionError(throwable) {
        this.errorManager.handleError(throwable);
    }

    render(event) {
        this.trigger('render', event);
    }

    activate() {
        this.trigger('activate');
    }

    deactivate() {
        this.trigger('deactivate');
    }

    onActivate() {
        if (this.isActive) {
            return;
        }
        this.isActive = true;
    }

    onDeactivate() {
        if (!this.isActive) {
            return;
        }
        this.isActive = false;
    }
}
}
{
let
    Object = engine.lang.type.Object,
    Type = engine.lang.utility.Type;

engine.fileManager.component.ErrorManager = class extends Object {

    handleError(error) {
        if (Type.isString(error)) {
            this.showErrorMessage(error);
        }
    }

    showErrorMessage(message) {
        let
            container = this.container,
            flash = document.createElement('div');

        flash.appendChild(document.createTextNode(message));
        flash.className = 'flash errors';

        container.appendChild(flash);
        setTimeout(() => {
            container.removeChild(flash);
        }, 3000);
    }
}
}
{
let
    Obj = engine.lang.utility.Object,
    Size = engine.fileManager.value.Size,
    Arr = engine.lang.utility.Array,
    Type = engine.lang.utility.Type;

engine.fileManager.setting.Config = class {

    constructor(raw) {
        Obj.merge(this, raw);
    }

    set uploadMaxFileSize(value) {
        this._uploadMaxFileSize = new Size(value);
    }

    get uploadMaxFileSize() {
        return this._uploadMaxFileSize;
    }

    isMimeTypeAllowed(type) {
        if (Type.isArray(this.allowMimeTypes)) {
            return Arr.contains(this.allowMimeTypes, type);
        }

        if (Type.isArray(this.denyMimeTypes)) {
            return !Arr.contains(this.denyMimeTypes, type);
        }

        return true;
    }

    isFileSizeAllowed(size) {
        return this.uploadMaxFileSize.value >= size;
    }

    isNumberOfUploadFilesAllowed(number) {
        return !this.maxNumberOfUploadFiles || this.maxNumberOfUploadFiles >= number;
    }
}
}
{
let
    Component = engine.react.Component,
    User = engine.web.component.User,
    Client = engine.fileManager.component.Client,
    WebClient = engine.web.component.Client,
    Layout = engine.fileManager.view.Layout,
    FileModel = engine.fileManager.model.File,
    FileListController = engine.fileManager.controller.FileList,
    ErrorManager = engine.fileManager.component.ErrorManager,
    Config = engine.fileManager.setting.Config;

let self = engine.fileManager.Application = class extends Component {

    initialize() {
        this.wrapper = this.wrapper || document.body;
        this.csrfTokenName = this.csrfTokenName || 'CSRF-Token';
        this.configUrl = this.configUrl + this.csrfTokenName + '=' + this.csrfToken;

        this.loadConfig({
            onSuccess: event => {
                this.rawConfig = JSON.parse(event.response.body);
                this.run();
            }
        });
    }

    get instanceId() {
        if (!this._instanceId) {
            if (!self.instanceCounter) {
                self.instanceCounter = 0;
            }
            this._instanceId = 'engine.fileManager.Application-' + self.instanceCounter++;
        }

        return this._instanceId;
    }

    get config() {
        if (!this._config) {
            this._config = new Config(this.rawConfig);
        }

        return this._config;
    }

    get layout() {
        if (!this._layout) {
            this._layout = new Layout({
                config: this.config,
                wrapper: this.wrapper
            });
        }

        return this._layout;
    }

    get errorManager() {
        if (!this._errorManager) {
            this._errorManager = new ErrorManager({
                container: this.layout.element
            });
        }

        return this._errorManager;
    }

    get client() {
        if (!this._client) {
            this._client = new Client({
                serverUrl: this.config.serverUrl,
                requestProgress: this.layout.requestProgress,
                errorManager: this.errorManager,
                csrfTokenName: this.csrfTokenName,
                csrfToken: this.csrfToken
            });
        }

        return this._client;
    }

    get user() {
        if (!this._user) {
            this._user = new User({
                applicationId: this.instanceId,
                expires: 24 * 3600000
            });
        }

        return this._user;
    }

    get fileModel() {
        if (!this._fileModel) {
            this._fileModel = new FileModel({
                client: this.client
            });
        }

        return this._fileModel;
    }

    get rightController() {
        if (!this._rightController) {
            this._rightController = new FileListController({
                view: this.layout.rightList,
                layout: this.layout,
                directory: this.user.read('rightDirectory', {id: '/'}),
                fileModel: this.fileModel,
                errorManager: this.errorManager,
                config: this.config,
                user: this.user
            });
        }

        return this._rightController;
    }

    get leftController() {
        if (!this._leftController) {
            this._leftController = new FileListController({
                view: this.layout.leftList,
                layout: this.layout,
                directory: this.user.read('leftDirectory', {id: '/'}),
                fileModel: this.fileModel,
                errorManager: this.errorManager,
                config: this.config,
                user: this.user
            });
        }

        return this._leftController;
    }

    run() {
        this.leftController.setDouble(this.rightController);
        this.leftController.view.activate();
        this.layout.render();

        this.trigger('ready');
    }

    loadConfig(...observers) {
        (new WebClient()).exchange({
            method: 'GET',
            url: this.configUrl
        }, ...observers);
    }
}
}
