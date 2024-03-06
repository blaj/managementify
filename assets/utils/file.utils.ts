export const downloadFile = (blob: Blob, filename: string): void => {
  const anchor = document.createElement('a');
  anchor.href = URL.createObjectURL(blob);
  anchor.download = filename;

  document.body.append(anchor);

  anchor.click();
  anchor.remove();

  setTimeout(() => URL.revokeObjectURL(anchor.href), 7000);
};
