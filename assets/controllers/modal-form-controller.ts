import { Controller } from '@hotwired/stimulus';
import { Modal } from 'mdb-ui-kit';
import { FrameElement, TurboBeforeFetchResponseEvent, visit } from '@hotwired/turbo';
import { FileUtils } from '@assets/utils';

export default class extends Controller<HTMLElement> {
  static targets = ['modal', 'frame'];

  static values = {
    formUrl: String
  };

  declare readonly modalTarget: HTMLDivElement;
  declare readonly frameTarget: FrameElement;

  modal: Modal = null;

  connect = (): void => {
    document.addEventListener('turbo:before-fetch-response', this.beforeFetchResponse);
  };

  disconnect = (): void => {
    document.removeEventListener('turbo:before-fetch-response', this.beforeFetchResponse);
  };

  openModal = async ({ params: { src } }: { params: { src?: string } }): Promise<void> => {
    if (src !== null) {
      this.frameTarget.src = src;
    }

    if (!this.modal) {
      this.modal = new Modal(this.modalTarget);
    } else {
      await this.frameTarget.reload();
    }

    this.modal.show();
  };

  private beforeFetchResponse = (event: TurboBeforeFetchResponseEvent): void => {
    if (!this.modal || !this.modal._isShown) {
      return;
    }

    const fetchResponse = event.detail.fetchResponse;
    const responseHeaders = fetchResponse.response.headers;
    const hasDownloadFile = responseHeaders.has('content-disposition');

    if (fetchResponse.succeeded) {
      event.preventDefault();
      this.modal.hide();

      if (hasDownloadFile) {
        const filename = responseHeaders
          .get('content-disposition')
          .split('filename=')[1]
          .split(';')[0];

        fetchResponse.response
          .blob()
          .then(blob => FileUtils.downloadFile(blob, filename))
          .catch(error => console.log(error));
      }

      if (fetchResponse.redirected) {
        visit(fetchResponse.location);
      }
    }
  };
}
