export default {

    inputSelector: 'calibrationFile',
    resultSelector: 'result',
    inputEvent: 'input',
    fileEncoding: 'UTF-8',
    regex: /(?=(\d|one|two|three|four|five|six|seven|eight|nine))/g,

    /**
     * Map human-readable digits to their values
     */
    humanDigitMapping: {
        one: 1,
        two: 2,
        three: 3,
        four: 4,
        five: 5,
        six: 6,
        seven: 7,
        eight: 8,
        nine: 9
    },

    /**
     * Initialise calibration decoder
     */
    init () {
        this.registerEventListeners()
    },

    /**
     * Decode calibration data
     *
     * @param {string} content
     */
    decode (content) {
        let result = 0

        // Split the content into individual lines
        const lines = content.split(/\r?\n|\r|\n/g);

        // Decode each line and add their result to the final answer
        lines.forEach((line) => {
            result += this.decodeLine(line)
        })

        this.displayResult(result)
    },

    /**
     * Decode line
     *
     * @param {string} line
     * @returns {number}
     */
    decodeLine (line) {
        const digits = Array.from(line.matchAll(this.regex))

        if (!digits.length) return 0

        return parseInt(
            this.parseDigit(digits[0][1]) + this.parseDigit(digits[digits.length - 1][1])
        )
    },

    /**
     * Parse digit
     *
     * @param {string|number} digit
     * @returns {string}
     */
    parseDigit (digit) {
        if (/\d/.test(digit)) return digit
        return this.humanDigitMapping[digit].toString()
    },

    /**
     * Handle upload of calibration data file
     *
     * @param {File} file
     */
    handleUpload (file) {
        const fileReader = new FileReader()

        // Read file content as text and pass to decode method
        fileReader.onload = (fileLoadedEvent) => {
            this.decode(fileLoadedEvent.target.result)
        };

        fileReader.readAsText(file, this.fileEncoding);
    },

    /**
     * Display result
     *
     * @param {number} result
     */
    displayResult (result) {
        document.getElementById(this.resultSelector).innerText = result.toString()
    },

    /**
     * Register event listeners
     */
    registerEventListeners () {
        document.getElementById(this.inputSelector)
            .addEventListener(this.inputEvent, (event) => {
                // Do nothing if we don't have a file
                if (event.target.files[0] === undefined) return

                // Handle file upload
                this.handleUpload(event.target.files[0])
            })
    }
}