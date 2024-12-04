import axios from 'axios'

class Form 
{
  constructor() {
    this.prefix = 'form'
    this.config = null;
    this.csrfName = document.querySelector('meta[name="csrf_name"]').content
    this.csrfValue = document.querySelector('meta[name="csrf_value"]').content
    this.headers = {
      headers: {
        "X-CSRF-Name": this.csrfName,
        "X-CSRF-Value": this.csrfValue
      }
    }
  }

  async send(payload) {
    const { data } = await axios.post('api/form/send', payload, this.headers);
    console.log(data)
  }

  async loadConf(payload) {
    const { data } = await axios.post('api/form/config', payload, this.headers);
    this.config = data.conf
  }

  onSendingEvent() {
    this.config.map(key => {
      const form = document.getElementById('form[data-form="'+key+'"]')
      const button = form.querySelector('button[data-form="send"]')

      button.addEventListener('click', () => {
        if (this.validate(key)) {
          this.processed(key)
        }
      })
    })
  }

  validate(key) {
    const form = document.getElementById('form[data-form="'+key+'"]')
    let isValid = true
    this.config[key].map((params, index)  => {
      if (typeof params.required !== "undefined") {
        const element = form.querySelector(params.type+'[data-form="'+index+'"]')

        console.log(element)
      } 
    })
    return isValid
  }

  processed(key) {
    
  }

  find() {
    const forms = document.querySelectorAll('form')
    let keys = []

    Array.prototype.slice.call(forms).map(form => {
      if(typeof form.dataset[this.prefix] !== "undefined") {
        keys.push(form.dataset[this.prefix])
      }
    })
    this.loadConf({
      conf: keys
    })
    this.onSendingEvent()
  }
}

// Listener
window.addEventListener('DOMContentLoaded', () => {
  window.form = new Form()
  window.form.find()
})