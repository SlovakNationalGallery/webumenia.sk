<script>
const items = [
    {
        id: 1,
        authorLinks: [
            {
                label: 'Stredoeur\u00f3psky maliar z 1. polovice 18. storo\u010dia',
                url: 'http://127.0.0.1:8000/katalog?author=Stredoeur%C3%B3psky%20maliar%20z%201.%20polovice%2018.%20storo%C4%8Dia',
            },
        ],
        title: 'Kvety v pr\u00edrode',
        url: 'http://127.0.0.1:8000/item/SVK:SNG.O_1852/show',
        datingFormatted: '1730\u20131740',
        filters: [
            {
                url: 'http://localhost:8000/en/katalog?work_type=maliarstvo&object_type=easel+painting&topic=po%C4%BEovn%C3%ADcky+mot%C3%ADv&medium=canvas&years-range=1500%2C1927',
                attributes: [
                    { label: 'v\u00fdtvarn\u00fd druh', value: 'maliarstvo' },
                    {
                        label: '\u017e\u00e1ner',
                        value: 'po\u013eovn\u00edcky mot\u00edv',
                    },
                    { label: 'typ diela', value: 'easel painting' },
                ],
            },
            {
                url: 'http://localhost:8000/en/katalog?work_type=maliarstvo&object_type=easel+painting&topic=po%C4%BEovn%C3%ADcky+mot%C3%ADv&medium=canvas&years-range=1500%2C1927',
                attributes: [
                    { label: 'v\u00fdtvarn\u00fd druh', value: 'maliarstvo' },
                    { label: 'typ diela', value: 'easel painting' },
                    { label: 'materi\u00e1l', value: 'canvas' },
                ],
            },
        ],
        img: {
            src: 'http://127.0.0.1:8000/storage/media/8/file.jpeg',
            srcset: 'http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_1600_929.jpeg 1600w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_1338_776.jpeg 1338w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_1120_650.jpeg 1120w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_937_544.jpeg 937w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_783_454.jpeg 783w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_655_380.jpeg 655w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_548_318.jpeg 548w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_459_266.jpeg 459w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_384_222.jpeg 384w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_321_186.jpeg 321w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_268_155.jpeg 268w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_224_130.jpeg 224w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_188_109.jpeg 188w, http://127.0.0.1:8000/storage/media/8/responsive-images/file___media_library_original_157_91.jpeg 157w, data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTYwMCA5MjkiPgoJPGltYWdlIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjkyOSIgeGxpbms6aHJlZj0iZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCwvOWovNEFBUVNrWkpSZ0FCQVFFQVlBQmdBQUQvL2dBN1ExSkZRVlJQVWpvZ1oyUXRhbkJsWnlCMk1TNHdJQ2gxYzJsdVp5QkpTa2NnU2xCRlJ5QjJPVEFwTENCeGRXRnNhWFI1SUQwZ09UQUsvOXNBUXdBREFnSURBZ0lEQXdNREJBTURCQVVJQlFVRUJBVUtCd2NHQ0F3S0RBd0xDZ3NMRFE0U0VBME9FUTRMQ3hBV0VCRVRGQlVWRlF3UEZ4Z1dGQmdTRkJVVS85c0FRd0VEQkFRRkJBVUpCUVVKRkEwTERSUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVLzhBQUVRZ0FFZ0FlQXdFaUFBSVJBUU1SQWYvRUFCOEFBQUVGQVFFQkFRRUJBQUFBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUUFBSUJBd01DQkFNRkJRUUVBQUFCZlFFQ0F3QUVFUVVTSVRGQkJoTlJZUWNpY1JReWdaR2hDQ05Dc2NFVlV0SHdKRE5pY29JSkNoWVhHQmthSlNZbktDa3FORFUyTnpnNU9rTkVSVVpIU0VsS1UxUlZWbGRZV1ZwalpHVm1aMmhwYW5OMGRYWjNlSGw2ZzRTRmhvZUlpWXFTazVTVmxwZVltWnFpbzZTbHBxZW9xYXF5czdTMXRyZTR1YnJDdzhURnhzZkl5Y3JTMDlUVjF0ZlkyZHJoNHVQazVlYm42T25xOGZMejlQWDI5L2o1K3YvRUFCOEJBQU1CQVFFQkFRRUJBUUVBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUkFBSUJBZ1FFQXdRSEJRUUVBQUVDZHdBQkFnTVJCQVVoTVFZU1FWRUhZWEVUSWpLQkNCUkNrYUd4d1Frak0xTHdGV0p5MFFvV0pEVGhKZkVYR0JrYUppY29LU28xTmpjNE9UcERSRVZHUjBoSlNsTlVWVlpYV0ZsYVkyUmxabWRvYVdwemRIVjJkM2g1ZW9LRGhJV0doNGlKaXBLVGxKV1dsNWlabXFLanBLV21wNmlwcXJLenRMVzJ0N2k1dXNMRHhNWEd4OGpKeXRMVDFOWFcxOWpaMnVMajVPWG01K2pwNnZMejlQWDI5L2o1K3YvYUFBd0RBUUFDRVFNUkFEOEErUGRNSzZyZFJ3RVlVbnJYWVRYZGo0YmhOc1Yza2pyWEo2SW45alg2U1RmdzlSUytKOVJHcVhaa2o2ZTFmSnlqenlzdGoxMCtXTGIzS3QxZVJ5WFV1MFlSdWxaelFBc2FsZ2hCWWIrRFdqYzZVaUlqQ1FmTjJyYUxqQmlmUFZWcmJEL0VIRjA5WjF0eXArdEZGRWY0YUlxL3hXVHpENWwrbE9tSktKeWFLS1VlaFQ2bi85az0iPgoJPC9pbWFnZT4KPC9zdmc+ 32w',
        },
    },
    {
        id: 6,
        authorLinks: [
            {
                label: 'Jaroslav V\u011b\u0161\u00edn',
                url: 'http://127.0.0.1:8000/katalog?author=V%C4%9B%C5%A1%C3%ADn%2C%20Jaroslav',
            },
            {
                label: 'Jaroslav V\u011b\u0161\u00edn',
                url: 'http://127.0.0.1:8000/katalog?author=V%C4%9B%C5%A1%C3%ADn%2C%20Jaroslav',
            },
        ],
        title: 'Ako sa t\u00e1 na\u0161a dedinka vol\u00e1..?',
        url: 'http://127.0.0.1:8000/item/SVK:SNG.O_1871/show',
        datingFormatted: '1887',
        filters: [
            {
                url: 'http://localhost:8000/katalog?work_type=maliarstvo%2Fobraz+z%C3%A1vesn%C3%BD&technique=olej&gallery=Slovensk%C3%A1+n%C3%A1rodn%C3%A1+gal%C3%A9ria%2C+SNG&years-range=1500%2C1927',
                attributes: [
                    {
                        label: 'v\u00fdtvarn\u00fd druh',
                        value: 'obraz z\u00e1vesn\u00fd',
                    },
                    {
                        label: 'gal\u00e9ria',
                        value: 'Slovensk\u00e1 n\u00e1rodn\u00e1 gal\u00e9ria',
                    },
                    { label: 'technika', value: 'olej' },
                ],
            },
        ],
        img: {
            src: 'http://127.0.0.1:8000/storage/media/12/file.jpeg',
            srcset: 'http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_1600_900.jpeg 1600w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_1338_752.jpeg 1338w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_1119_629.jpeg 1119w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_937_527.jpeg 937w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_783_440.jpeg 783w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_655_368.jpeg 655w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_548_308.jpeg 548w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_459_258.jpeg 459w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_384_216.jpeg 384w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_321_180.jpeg 321w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_268_150.jpeg 268w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_224_126.jpeg 224w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_188_105.jpeg 188w, http://127.0.0.1:8000/storage/media/12/responsive-images/file___media_library_original_157_88.jpeg 157w, data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTYwMCA5MDAiPgoJPGltYWdlIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjkwMCIgeGxpbms6aHJlZj0iZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCwvOWovNEFBUVNrWkpSZ0FCQVFFQVlBQmdBQUQvL2dBN1ExSkZRVlJQVWpvZ1oyUXRhbkJsWnlCMk1TNHdJQ2gxYzJsdVp5QkpTa2NnU2xCRlJ5QjJPVEFwTENCeGRXRnNhWFI1SUQwZ09UQUsvOXNBUXdBREFnSURBZ0lEQXdNREJBTURCQVVJQlFVRUJBVUtCd2NHQ0F3S0RBd0xDZ3NMRFE0U0VBME9FUTRMQ3hBV0VCRVRGQlVWRlF3UEZ4Z1dGQmdTRkJVVS85c0FRd0VEQkFRRkJBVUpCUVVKRkEwTERSUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVLzhBQUVRZ0FFZ0FnQXdFaUFBSVJBUU1SQWYvRUFCOEFBQUVGQVFFQkFRRUJBQUFBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUUFBSUJBd01DQkFNRkJRUUVBQUFCZlFFQ0F3QUVFUVVTSVRGQkJoTlJZUWNpY1JReWdaR2hDQ05Dc2NFVlV0SHdKRE5pY29JSkNoWVhHQmthSlNZbktDa3FORFUyTnpnNU9rTkVSVVpIU0VsS1UxUlZWbGRZV1ZwalpHVm1aMmhwYW5OMGRYWjNlSGw2ZzRTRmhvZUlpWXFTazVTVmxwZVltWnFpbzZTbHBxZW9xYXF5czdTMXRyZTR1YnJDdzhURnhzZkl5Y3JTMDlUVjF0ZlkyZHJoNHVQazVlYm42T25xOGZMejlQWDI5L2o1K3YvRUFCOEJBQU1CQVFFQkFRRUJBUUVBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUkFBSUJBZ1FFQXdRSEJRUUVBQUVDZHdBQkFnTVJCQVVoTVFZU1FWRUhZWEVUSWpLQkNCUkNrYUd4d1Frak0xTHdGV0p5MFFvV0pEVGhKZkVYR0JrYUppY29LU28xTmpjNE9UcERSRVZHUjBoSlNsTlVWVlpYV0ZsYVkyUmxabWRvYVdwemRIVjJkM2g1ZW9LRGhJV0doNGlKaXBLVGxKV1dsNWlabXFLanBLV21wNmlwcXJLenRMVzJ0N2k1dXNMRHhNWEd4OGpKeXRMVDFOWFcxOWpaMnVMajVPWG01K2pwNnZMejlQWDI5L2o1K3YvYUFBd0RBUUFDRVFNUkFEOEErdGJlK2p0NFFaWENnZHlhc1ErSTdHWmhHbHdqTjZacjVGK0lIeHZ2N3l4VzN0SkRHejhaQnJ6ZTMrS2V0K0hyeUo1THhwSkNjNDNWODIrWmFIdWM4WHNmWlh4YThRTnAyaFN0YnVBK0t4UGc1NHBON3BMbTVsRzhIdlhoZXFmRnVYeERwU0xjRWtsZWMxUTBMNGt4NkJiTUJONVlKOWFoUms1QTZrYkhtWGlWaUkwT1Qxcmo1M1o5WGkzTVc2ZFRSUlc4dHpLbHNkN2Evd0RIc3RjbDR0ZGx5QXhIME5GRlhUM1JsUDRXZi8vWiI+Cgk8L2ltYWdlPgo8L3N2Zz4= 32w',
        },
    },
    {
        id: 16,
        authorLinks: [
            {
                label: 'Nemeck\u00fd maliar okolo polovice 19. storo\u010dia',
                url: 'http://127.0.0.1:8000/katalog?author=Nemeck%C3%BD%20maliar%20okolo%20polovice%2019.%20storo%C4%8Dia',
            },
        ],
        title: 'Podobize\u0148 \u017eeny',
        url: 'http://127.0.0.1:8000/item/SVK:SNG.O_2100/show',
        datingFormatted: '1840\u20131860',
        filters: [
            {
                url: 'https://www.webumenia.sk/katalog?work_type=grafika&object_type=autorsk%C3%BD+album&medium=kart%C3%B3n&years-range=-470%2C2021',
                attributes: [
                    { label: 'v\u00fdtvarn\u00fd druh', value: 'grafika' },
                    { label: 'typ diela', value: 'autorsk\u00fd album' },
                    { label: 'materi\u00e1l', value: 'kart\u00f3n' },
                ],
            },
        ],
        img: {
            src: 'http://127.0.0.1:8000/storage/media/17/file.jpeg',
            srcset: 'http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_1600_900.jpeg 1600w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_1338_752.jpeg 1338w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_1120_630.jpeg 1120w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_937_527.jpeg 937w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_783_440.jpeg 783w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_655_368.jpeg 655w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_548_308.jpeg 548w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_459_258.jpeg 459w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_384_216.jpeg 384w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_321_180.jpeg 321w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_268_150.jpeg 268w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_224_126.jpeg 224w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_188_105.jpeg 188w, http://127.0.0.1:8000/storage/media/17/responsive-images/file___media_library_original_157_88.jpeg 157w, data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTYwMCA5MDAiPgoJPGltYWdlIHdpZHRoPSIxNjAwIiBoZWlnaHQ9IjkwMCIgeGxpbms6aHJlZj0iZGF0YTppbWFnZS9qcGVnO2Jhc2U2NCwvOWovNEFBUVNrWkpSZ0FCQVFFQVlBQmdBQUQvL2dBN1ExSkZRVlJQVWpvZ1oyUXRhbkJsWnlCMk1TNHdJQ2gxYzJsdVp5QkpTa2NnU2xCRlJ5QjJPVEFwTENCeGRXRnNhWFI1SUQwZ09UQUsvOXNBUXdBREFnSURBZ0lEQXdNREJBTURCQVVJQlFVRUJBVUtCd2NHQ0F3S0RBd0xDZ3NMRFE0U0VBME9FUTRMQ3hBV0VCRVRGQlVWRlF3UEZ4Z1dGQmdTRkJVVS85c0FRd0VEQkFRRkJBVUpCUVVKRkEwTERSUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVLzhBQUVRZ0FFZ0FnQXdFaUFBSVJBUU1SQWYvRUFCOEFBQUVGQVFFQkFRRUJBQUFBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUUFBSUJBd01DQkFNRkJRUUVBQUFCZlFFQ0F3QUVFUVVTSVRGQkJoTlJZUWNpY1JReWdaR2hDQ05Dc2NFVlV0SHdKRE5pY29JSkNoWVhHQmthSlNZbktDa3FORFUyTnpnNU9rTkVSVVpIU0VsS1UxUlZWbGRZV1ZwalpHVm1aMmhwYW5OMGRYWjNlSGw2ZzRTRmhvZUlpWXFTazVTVmxwZVltWnFpbzZTbHBxZW9xYXF5czdTMXRyZTR1YnJDdzhURnhzZkl5Y3JTMDlUVjF0ZlkyZHJoNHVQazVlYm42T25xOGZMejlQWDI5L2o1K3YvRUFCOEJBQU1CQVFFQkFRRUJBUUVBQUFBQUFBQUJBZ01FQlFZSENBa0tDLy9FQUxVUkFBSUJBZ1FFQXdRSEJRUUVBQUVDZHdBQkFnTVJCQVVoTVFZU1FWRUhZWEVUSWpLQkNCUkNrYUd4d1Frak0xTHdGV0p5MFFvV0pEVGhKZkVYR0JrYUppY29LU28xTmpjNE9UcERSRVZHUjBoSlNsTlVWVlpYV0ZsYVkyUmxabWRvYVdwemRIVjJkM2g1ZW9LRGhJV0doNGlKaXBLVGxKV1dsNWlabXFLanBLV21wNmlwcXJLenRMVzJ0N2k1dXNMRHhNWEd4OGpKeXRMVDFOWFcxOWpaMnVMajVPWG01K2pwNnZMejlQWDI5L2o1K3YvYUFBd0RBUUFDRVFNUkFEOEEvT2JWZHMwaFplS3UrRE5GVFd0VmpnYzRCTlpOd3pPK0J5SzdmNGVhZkdsMHQwMHl4bFQzTmVaaXB1RkdUanVkR0hpblVYTnNKNDA4Si84QUNQWEtvcDNLUldEREh0R0RYcC9qZUdEVjRsbCswSVdVZGpYbU41SWx2SXczQTRyZ3krdE9yVFNudjFPdkdVNFJxUGsyTWExNVpzODFxV1VqSWpiV0svUTBVVjYwOW1lYkhjdHpUU0dFWmRqK05ZRjZUNW5VMFVWRkhxYVMzUi8vMlE9PSI+Cgk8L2ltYWdlPgo8L3N2Zz4= 32w',
        },
    },
]

// Shuffles an array
// https://stackoverflow.com/a/2450976
function shuffle(array) {
    let currentIndex = array.length,
        randomIndex

    // While there remain elements to shuffle.
    while (currentIndex != 0) {
        // Pick a remaining element.
        randomIndex = Math.floor(Math.random() * currentIndex)
        currentIndex--

            // And swap it with the current element.
            ;[array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]]
    }

    return array
}

function pickRandom(array) {
    return array[Math.floor(Math.random() * (array.length - 1))]
}

async function sleep(timeInMs) {
    await new Promise(r => setTimeout(r, timeInMs));
}

export default {
    data() {
        const shuffledItems = shuffle(items)
        const initialFilter = pickRandom(shuffledItems[0].filters)

        return {
            items,
            shuffledItems,
            itemIndex: 0,
            isShuffling: false,
            filter: initialFilter,
        }
    },
    computed: {
        nextItemIndex() {
            return (this.itemIndex + 1) % this.items.length
        },
        item() {
            const { title, authorLinks, datingFormatted, url } = this.shuffledItems[this.itemIndex]
            return {
                title,
                authors: authorLinks.map((al) => al.label).join(', '),
                dating: datingFormatted,
                url,
            }
        },
    },
    // props: {
    //     id: {
    //         type: Number,
    //         required: true,
    //     },
    //     url: {
    //         type: String,
    //         required: true,
    //     },
    // },
    render() {
        return this.$scopedSlots.default({
            isShuffling: this.isShuffling,
            filter: this.filter,
            item: this.item,
            img: this.shuffledItems[this.itemIndex].img,
            nextImg: this.shuffledItems[this.nextItemIndex].img,
            shuffle: async () => {
                if (this.isShuffling) return

                this.isShuffling = true

                const nextItem = this.shuffledItems[this.nextItemIndex]
                const nextFilter =
                    nextItem.filters[Math.floor(Math.random() * (nextItem.filters.length - 1))]

                // Start shuffling filters
                const shuffledFilters = shuffle(this.items.flatMap((si) => si.filters))
                const numberOfShuffles = 4

                await sleep(300)

                for (let iteration = 0; iteration < numberOfShuffles - 1; iteration++) {
                    const shuffledFiltersIndex = iteration % shuffledFilters.length

                    this.filter = {
                        ...this.filter,
                        attributes: shuffle(shuffledFilters[shuffledFiltersIndex].attributes),
                    }

                    await sleep(400)
                }

                // Settle filters
                this.filter = nextFilter

                // Change image
                await sleep(400)
                this.itemIndex = this.nextItemIndex
                this.isShuffling = false
            },
        })
    },
}
</script>
